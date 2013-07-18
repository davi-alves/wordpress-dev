Flying = Flying || {}

Flying.Admin = ( ($)->
  #- elements
  __addNew = '.slide-add-new'
  __remove = '.remove'
  __list = '.slide-list tbody'
  __item = '.slide-item'
  __emptyList = ''

  getEmptyList = ->
    $.ajax(
      url: Flying.url
      type: 'post'
      dataType: 'html'
      data:
        action: Flying.admin_action
        ajaxAction: 'empty_line'
        nonce: Flying.nonce
    ).done((data) ->
      __emptyList = data
    ).fail(->
      console.log 'Oh noes!'
    )


  sortableList = ->
    $(__list).sortable(
      placeholder: 'placeholder'
      forcePlaceholderSize: true
      opacity: 0.8
    )
    $(__list).disableSelection()

  showImageUpload = ->
    window.tb_show '', 'media-upload.php?type=image&TB_iframe=true'

  handleUploadChoise = (html)->
    image = jQuery('img',html)
    imageId = image.attr('class').replace(/(.*?)wp-image-/, '')
    window.tb_remove();
    $.ajax(
      url: Flying.url
      type: 'post'
      dataType: 'html'
      data:
        action: Flying.admin_action
        ajaxAction: 'new_line'
        image:
          id: imageId
        nonce: Flying.nonce
    ).done((data) ->
      list = $(__list)
      list.empty() if countList() == 0
      list.append data
    ).fail(->
      console.log 'Oh noes!'
    )

  countList = ->
    $(__list).find(__item).length

  deleteItem = (item) ->
    if countList() > 1
      item.remove()
    else
      $(__list).empty().append __emptyList

  bindEvents = ->
    getEmptyList()
    #- bind new slide link
    $(__addNew).bind 'click', (event) ->
      event.preventDefault()
      showImageUpload()
    #- bind remove buttom
    $(__list).find(__item).find(__remove).bind 'click', (event) ->
      event.preventDefault()
      item = $(this).parents(__item)
      deleteItem item
    #- exposes upload handle to global scope
    window['send_to_editor'] = handleUploadChoise;
    #- sortable table
    sortableList()

  {
    init: ->
      bindEvents()
  }
)(jQuery)

jQuery(->
  Flying.Admin.init()
)
