//Ajax enabled PhoneGap apps Must include this file
$(document).on('pagecreate', '[data-role=page]', function(){	
  $('a').each(function(){
    var href=jQuery(this).attr('href');
    if(href && href.search(/^http/) == -1)
    {
      $(this).attr('href',href.replace('.php','.html'));
    }
  });
});
