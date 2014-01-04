function IncludeJavaScript(jsFile)
{
  document.write('<script type="text/javascript" src="'+jsFile +'"></script>');
}
IncludeJavascript('http://smsburuhmigran.infest.or.id/extensions/js/jcarousellite_1.0.1c4.js');
$(document).ready(function() {
       $('head').append('<link rel="stylesheet" href="http://smsburuhmigran.infest.or.id/extensions/css/style.css" type="text/css" />');

       //$('head').append('<script type="text/javascript" src="http://smsburuhmigran.infest.or.id/extensions/js/jcarousellite_1.0.1c4.js"></script>');
       //$('head').append('<script type="text/javascript" src="http://smsburuhmigran.infest.or.id/extensions/js/widget.data.js"></script>');
       
       
       //$.getScript('http://smsburuhmigran.infest.or.id/extensions/js/widget.data.js');
       getSMS(); 
});

