( ($, window) ->
  Flyingkrai = window.Flyingkrai || {}

  Flyingkrai.FeaturedSlideshow = ( ->
    #- elements
    __container = '.superbanner'
    __containerElement = null
    __images = 'ul.images'
    __imageItens = "#{__images} li"
    __thumbs = 'ul.min'
    __activeClass = 'active'
    __active = ".#{__activeClass}"

    changeActive = (element) ->
      elementIndex = element.index()
      current = __containerElement.find(__images).find __active
      return false if current.index() == elementIndex

      actual = $(__containerElement.find(__imageItens)[elementIndex])
      current.stop(true, true).fadeOut(500, ->
        $(this).removeClass __activeClass
        actual.addClass(__activeClass).fadeIn(1000)
      )


    setFirst = ->
      first = __containerElement.find(__imageItens).filter(':first').addClass __activeClass
      __containerElement.find(__imageItens).not(first).hide()

    bindEvents = ->
      __containerElement.find(__thumbs).bind 'click', (event) ->
        event.preventDefault()
        changeActive $(@)

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
