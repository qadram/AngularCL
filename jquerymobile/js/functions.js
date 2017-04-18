jQuery(document).bind('mobileinit', function(){
  /**
  * jQuery code to handle the ajax calls
  * First we prevent all form submisions and replace it to ajax calls
  * Second, for all links with the attribute data-ajax set to true, we make an ajax call too
  */
  $(document).on('submit', 'div[data-ajax-url] form', function(e) {
    e.preventDefault();
    var url=jQuery.mobile.activePage.attr('data-ajax-url');
    AjaxCall(url);
    return false;
  });

  $(document).on('click', 'a[data-ajax="true"]', function(e) {
    var url=jQuery(this).attr('href').split('?');
    var data=url[1];
    var url=jQuery.mobile.activePage.attr('data-ajax-url');
    AjaxCall(url,data);
    return false;
  });


});

// Disable page caching
$(document).on('pagehide', '[data-role="page"]', function(event, ui) {
  var page = jQuery(event.target);
  page.remove();
});

// This function calls the uri page for JSONP data and injects the results on the elements on the page,
// If there is an error displays a standard error message
function AjaxCall(uri,extradata)
{

  //allow cross domain scripting in jQuery 1.6.x
  jQuery.support.cors = true;

  var formdata= jQuery.mobile.activePage.find('form').serialize();
  if(extradata)
    formdata=formdata+'&'+extradata;
  var d = new Date();
  jQuery.mobile.showPageLoadingMsg();
  jQuery.ajax({
    url:uri+"?callback=json&d="+d.getTime(),
    dataType: 'json',
    type: 'POST',
    data: formdata,
    crossDomain: true,
    success:function(data){
        if(!data.redirect)
        {
          var page=jQuery.mobile.activePage;

          // replace all elements html
          if(data.elements)
          {
            jQuery.each(data.elements,function(i,v){
              page.find('#'+i).html(Base64.decode(v));
            });
          }

          // append css to page
          if(data.css)
          {
            var cssId      = page.attr('id')+'_css',
                cssElement = jQuery('#' + cssId, page),
				cssContent = Base64.decode(data.css);
				cssStyleElement =
					$('<style id="'+ cssId +'" type="text/css">'
								   + cssContent
				    + '</style>')

            cssElement.replaceWith(cssStyleElement);
          }

          //clean hidden dialogs if any
          jQuery('.ui-selectmenu,.ui-selectmenu-screen').remove();
          jQuery('div[data-role="dialog"]').remove();
          page.page("destroy");
          //inactivate the page
          page.removeClass(jQuery.mobile.activePageClass);
          //refresh it
          page.page();
          //activate it again
          page.addClass(jQuery.mobile.activePageClass);
          jQuery.mobile.hidePageLoadingMsg();
        }
        else
        {
          location.href=data.redirect;
        }
      return false;
    },

    error:function(){

        // remove the loading circle
        jQuery.mobile.hidePageLoadingMsg();

        // raises the ajax call error event
        jQuery.mobile.activePage.trigger('ajaxcallerror');

        // default ajax call error (if exists)
        var page=jQuery.mobile.activePage.attr('id'),
            callback=page+'JSAjaxCallError';

        !window[callback] || eval(callback + '()');
    },

    // call AjaxCallComplete event
    complete:function(){

        jQuery.mobile.activePage.trigger('ajaxcallcomplete');
    }
  });


}

// JQueryfied version of jsWrapper in common.js
function jsWrapper(event,hiddenfield, submitvalue)
{
  event.preventDefault();

  jQuery('#'+hiddenfield).val(submitvalue);
  jQuery('#'+hiddenfield).parents('form').submit();
  jQuery('#'+hiddenfield).val('');
  return false;

}



// Adapt buttons to accept HTML5 Builder styles.
$(document).on('pagecreate', "div:jqmData(role='page')", function(e) {
  var page=jQuery(this);
  page.css('min-height','100%');

  //add upperclass modifier for custom themes
  jQuery('[data-upperclass]').each(function(){
    var el=jQuery(this);
    var myClass=el.data('upperclass');
    var name=el.attr('name');


    if(el.data('role') == "page")
    {
       el.closest('body').addClass(myClass);
    }
    else
    if(name)
    {
      var pos=name.indexOf('[');
      if(pos!=-1)
        name=name.substr(0,pos);
      page.find('#'+name+'_outer').addClass(myClass);
    }
    else
    {
      var id=el.attr('id');
      el.closest('[id*=_outer]').addClass(myClass);
      //page.find('#'+id+'_outer').addClass(myClass);
     }
  });

});


$(document).on('pageinit', "div:jqmData(role='page')", processPage);

$(document).on('pageinit', "div:jqmData(role='dialog')", processPage);

function processPage(){

    // getting the fixed size controls
    var fixedsize = $('[data-fixedsize="true"]').not('[data-role="none"]');

    // MButton
    fixedsize.filter("input[type=submit],input[type=button],input[type=reset],input[type=image]").each(function(i,el){

        var input  = $(this),
            button = input.closest('.ui-btn'),
            inner  = button.children('.ui-btn-inner'),
            text   = inner.children('.ui-btn-text');

        button.addClass('border-box no-margin');
        inner.addClass('expanded border-box');
        text.addClass('centered');

    });

    // MLink
    fixedsize.filter("a:jqmData(role='button')").each(function(i,el){

        var button = $(this),
            inner  = button.children('.ui-btn-inner')
            text   = inner.children('.ui-btn-text');

        button.addClass('border-box no-margin');
        inner.addClass('expanded border-box');
        text.addClass('centered');

        inner.css({
                'padding-top': '0px',
                'padding-bottom': '0px'
            });
    });

    // MCheckBox, MRadioButton
    fixedsize.filter('input[type=checkbox], input[type=radio]').each(function(i,el){

        var parent = $(this).parent(),
            button = parent.children('.ui-btn'),
            inner  = button.children('.ui-btn-inner'),
            text   = inner.children('.ui-btn-text');

        button.addClass('border-box no-margin expanded-child border-box-child expanded-centered-child');
        //inner.addClass('expanded border-box');
        //text.addClass('centered');

        // fixing table borders on MRadioButtons
        if(button.hasClass('ui-radio'))
        {
            button.closest('table').css({
                'border':0,
                'border-spacing':0,
                'padding':0,
                'margin':0,
                'table-layout': 'fixed'
            });
        }

        inner.css({
                'padding-top': '0px',
                'padding-bottom': '0px'
            });
    });

    // MComboBox
    fixedsize.filter('select').each(function(i,el){

        var parent = $(this).closest('.ui-select'),
            button = parent.children('.ui-btn'),
            inner  = button.children('.ui-btn-inner'),
            text   = inner.children('.ui-btn-text');

        button.addClass('border-box no-margin');
        inner.addClass('expanded border-box');
        text.addClass('centered');
    });

    // MToolBar
    fixedsize.filter('.ui-navbar').each(function(){

        var navbar = $(this),
            inners = $('.ui-btn-inner', navbar),
            texts  = $('.ui-btn-text', navbar);

        //inners.addClass('expanded border-box');
        //texts.addClass('centered');

        navbar.children('ul').css('height', '100%')
              .children('li').css('height', '100%')
              .children('a').css('height', '100%').addClass('border-box');

    });

    // MEdit
    fixedsize.filter('input[type=text],input[type=number],input[type=password],input[type=email],input[type=url],input[type=tel],input[type=time],input[type=date],input[type=datetime],input[type=month],input[type=week],input[type=datetime-local],input[type=color]').each(function(){

        var input = $(this),
            parent = $(this).parent();

        parent.addClass('no-margin border-box');
        parent.css('height', input.css('height'));
        input.addClass('no-margin border-box');
    });

    //MTextArea
    fixedsize.filter('textarea').addClass('no-margin border-box');

    // MEdit (search)
    fixedsize.filter('input[type=search], [data-type="search"]').each(function(){

        $(this).closest('.ui-input-search').addClass('no-margin border-box');
    });

    // MSlider
    fixedsize.filter('input[data-type=range]').each(function(){

        var input  = $(this),
            parent_div  = input.parent(),
            outer = parent_div.parent(),
            slider = input.next(),
            sliderWidth = outer.width() - (input.width() + 40);

            input.addClass('border-box');

            // fix the width and margin
            slider.css({
                'width': sliderWidth + 'px',
                'margin': '0 0 0 20px',
                'display': 'inline-block',
                'vertical-align': 'middle',
                'top': '0px'
            });

            input.css({
                'display': 'inline-block',
                'vertical-align': 'middle',
                'float': 'none'
            });

    });

    //MToggle
	fixedsize.filter('.ui-slider-switch').each(function(){

        var select = $(this),
            slider = select.next(),
            halfHeight = slider.outerHeight()/2;

        slider.css({'top':'50%', 'margin-top':'-' + halfHeight + 'px'});
    });


    // MCheckBoxGroup, MRadioGroup
    fixedsize.filter('[data-role=controlgroup]').addClass('no-margin');

    //MList
    fixedsize.filter('[data-role="listview"]').each(function(){

        var ul = $(this),
            filter = ul.prev();

        // the list
        ul.addClass('no-margin');
        ul.parent().css('overflow', 'auto');
        if(filter.length > 0)
        {
            filter.addClass('no-margin');
        }
    });;

    // MMap
    fixedsize.filter('.ui-map-wrap').addClass('no-margin');
	
	// MDateTimePicker - DurationBox	
	fixedsize.filter('input[data-role="datebox"]').each(function(){

        var input= $(this),
            parent = input.parents('.ui-input-text');			

		parent.addClass('no-margin border-box').css('height', '100%');        	
		
		if (input.data( "options" ).useInline === true)
			parent.css("display", "none");
		
    }); 
	
    //fix to visually uncheck components when the form is reset
    jQuery('form').bind('reset',function(){
      jQuery("input[type='checkbox']").not('input[data-role="none"]').attr('checked',false).checkboxradio("refresh");
      jQuery("input[type='radio']").not('input[data-role="none"]').attr('checked',false).checkboxradio("refresh");
      jQuery("input[data-type='range'],select[data-role='slider']").not('input[data-role="none"],select[data-role="none"]').each(function(){
        jQuery(this).val(this.defaultValue).slider('refresh');
      });
      jQuery('select[data-native-menu=false]').not('select[data-role="none"]').each(function(){
        jQuery(this).val(this.defaultValue).selectmenu('refresh');
      });
    });
}

