var RefreshPreview = false;

! function ($) {
    $(function () {
        hljs.initHighlightingOnLoad();
        
        var $window = $(window)
        var $body = $(document.body)
        var $sideBar = $('.bs-sidebar')
        var navHeight = $('.navbar').outerHeight(true) + 10

        $body.scrollspy({
            target: '.bs-sidebar',
            offset: navHeight
        })

        $('.bs-docs-container [href=#]').click(function (e) {
            e.preventDefault()
        })

        $window.on('resize', function () {
            $body.scrollspy('refresh')
            // We were resized. Check the position of the nav box
            $sideBar.affix('checkPosition')
        })

        $window.on('load', function () {
            $body.scrollspy('refresh')
            $('.bs-top').affix();
            $sideBar.affix({
                offset: {
                    top: function () {
                        var offsetTop = $sideBar.offset().top
                        var sideBarMargin = parseInt($sideBar.children(0).css('margin-top'), 10)
                        var navOuterHeight = $('.bs-docs-nav').height()
                        return (this.top = offsetTop - navOuterHeight - sideBarMargin);
                    },
                    bottom: function () {
                        return $('.bs-footer').outerHeight(true)
                    }
                }
            })
            setTimeout(function () {
                $sideBar.affix('checkPosition')
            }, 10);
            setTimeout(function () {
                $sideBar.affix('checkPosition')
            }, 100);
        });

        // tooltip demo
        $('.tooltip-demo').tooltip({
            selector: "[data-toggle=tooltip]",
            container: "body"
        })

        $('.tooltip-test').tooltip()
        $('.popover-test').popover()

        $('.bs-docs-navbar').tooltip({
            selector: "a[data-toggle=tooltip]",
            container: ".bs-docs-navbar .nav"
        });
        
        
        var sBuffer = $("#edit textarea").val();
        
        if(sBuffer != ""){
            $("#edit textarea").val(base64_decode(sBuffer));
            $("#edit textarea").change(function(){
                
                if(!RefreshPreview){
                    RefreshPreview = true;
                    
                    $.post("/preview", {"contents": base64_encode($("#edit textarea").val())}, function(mData){
                        $("#preview").html(mData);

                        var blocks = document.querySelectorAll('pre code');
                        Array.prototype.forEach.call(blocks, hljs.highlightBlock);

                        RefreshPreview = false;
                    });
                }
            })
        }
        
        $("#save").click(function(){
            $.post(window.location.href, {"contents": base64_encode($("#edit textarea").val())}, function(){
                window.location = window.location.href;
            });
        });
    })
}(window.jQuery);

function base64_encode(data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  do { // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}

function base64_decode(data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    dec = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data += '';

  do { // unpack four hexets into three octets using index points in b64
    h1 = b64.indexOf(data.charAt(i++));
    h2 = b64.indexOf(data.charAt(i++));
    h3 = b64.indexOf(data.charAt(i++));
    h4 = b64.indexOf(data.charAt(i++));

    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;

    o1 = bits >> 16 & 0xff;
    o2 = bits >> 8 & 0xff;
    o3 = bits & 0xff;

    if (h3 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1);
    } else if (h4 == 64) {
      tmp_arr[ac++] = String.fromCharCode(o1, o2);
    } else {
      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
    }
  } while (i < data.length);

  dec = tmp_arr.join('');

  return dec.replace(/\0+$/, '');
}