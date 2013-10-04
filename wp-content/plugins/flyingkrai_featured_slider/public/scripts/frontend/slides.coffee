( ($, window) ->
  Flyingkrai = window.Flyingkrai || {}

  Flyingkrai.FeaturedSlideshow = ( ->
    #- elements
    __container        = '.superbanner'
    __containerElement = null
    __images           = 'ul.images'
    __imageItens       = "#{__images} li"
    __thumbs           = 'ul.min'
    __activeClass      = 'active'
    __active           = ".#{__activeClass}"
    #- helper
    __rotateTimeout    = 2500
    __timeout          = null

    changeActive = (element) ->
      elementIndex = element.index()
      current = __containerElement.find(__images).find __active
      return false if current.index() == elementIndex
      next = $(__containerElement.find(__imageItens)[elementIndex])
      doChange current, next

    doChange = (current, next) ->
      current.stop(true, true).fadeOut(500, ->
        $(this).removeClass __activeClass
        next.addClass(__activeClass).fadeIn(1000)
      )

    rotate = ->
      items = __containerElement.find(__imageItens)
      return false if items.length == 0
      current = items.filter(__active)
      return false if current.length == 0

      next = current.next()
      if next.length == 0
        next = items.filter ':first'

      __timeout = setTimeout(->
        doChange current, next
        rotate()
      , __rotateTimeout)

    setFirst = ->
      first = __containerElement.find(__imageItens).filter(':first').addClass __activeClass
      __containerElement.find(__imageItens).not(first).hide()

    bindEvents = ->
      rotate()
      __containerElement.find(__thumbs).bind 'click', (event) ->
        event.preventDefault()
        changeActive $(@)
      __containerElement.bind('mouseenter ', ->
        clearTimeout __timeout
      ).bind('mouseleave', ->
        rotate()
      )

    {
      init: ->
        __containerElement = $(__container)
        return false if __containerElement.length == 0
        setFirst()
        bindEvents()
    }
  )()

  $(->
    Flyingkrai.FeaturedSlideshow.init()
  )
)(jQuery, window)
