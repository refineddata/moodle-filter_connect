  var mywin;
  function popconference(PopURL) {
    var l = 1 ;
    var t = 1 ;
    var w = screen.availWidth ;
    var h = screen.availHeight ;
    var winname = 'connect' ;
    var options = 'scrollbars=1,resizable,dependant,fullscreen'
    var newwin = window.open(PopURL, winname,
                             'width=' + w + ',height=' + h +
                             ',left=' + l + ',top=' + t + ',' + options)
  if (window.focus) {
     newwin.focus()
  }
  return newwin;
}
