jQuery.extend({createUploadIframe:function(a,b){var c="jUploadFrame"+a,d='<iframe id="'+c+'" name="'+c+'" style="position:absolute; top:-9999px; left:-9999px"';return window.ActiveXObject&&(typeof b=="boolean"?d+=' src="javascript:false"':typeof b=="string"&&(d+=' src="'+b+'"')),d+=" />",jQuery(d).appendTo(document.body),jQuery("#"+c).get(0)},createUploadForm:function(a,b,c){var d="jUploadForm"+a,e="jUploadFile"+a,f=jQuery('<form  action="" method="POST" name="'+d+'" id="'+d+'" enctype="multipart/form-data"></form>');if(c)for(var g in c)jQuery('<input type="hidden" name="'+g+'" value="'+c[g]+'" />').appendTo(f);var h=jQuery("#"+b),i=jQuery(h).clone();return jQuery(h).attr("id",e),jQuery(h).before(i),jQuery(h).appendTo(f),jQuery(f).css("position","absolute"),jQuery(f).css("top","-1200px"),jQuery(f).css("left","-1200px"),jQuery(f).appendTo("body"),f},ajaxFileUpload:function(a){a=jQuery.extend({},jQuery.ajaxSettings,a);var b=(new Date).getTime(),c=jQuery.createUploadForm(b,a.fileElementId,typeof a.data=="undefined"?!1:a.data),d=jQuery.createUploadIframe(b,a.secureuri),e="jUploadFrame"+b,f="jUploadForm"+b;a.global&&!(jQuery.active++)&&jQuery.event.trigger("ajaxStart");var g=!1,h={};a.global&&jQuery.event.trigger("ajaxSend",[h,a]);var i=function(b){var d=document.getElementById(e);try{d.contentWindow?(h.responseText=d.contentWindow.document.body?d.contentWindow.document.body.innerHTML:null,h.responseXML=d.contentWindow.document.XMLDocument?d.contentWindow.document.XMLDocument:d.contentWindow.document):d.contentDocument&&(h.responseText=d.contentDocument.document.body?d.contentDocument.document.body.innerHTML:null,h.responseXML=d.contentDocument.document.XMLDocument?d.contentDocument.document.XMLDocument:d.contentDocument.document)}catch(f){jQuery.handleError(a,h,null,f)}if(h||b=="timeout"){g=!0;var i;try{i=b!="timeout"?"success":"error";if(i!="error"){var j=jQuery.uploadHttpData(h,a.dataType);a.success&&a.success(j,i),a.global&&jQuery.event.trigger("ajaxSuccess",[h,a])}else jQuery.handleError(a,h,i)}catch(f){i="error",jQuery.handleError(a,h,i,f)}a.global&&jQuery.event.trigger("ajaxComplete",[h,a]),a.global&&!--jQuery.active&&jQuery.event.trigger("ajaxStop"),a.complete&&a.complete(h,i),jQuery(d).unbind(),setTimeout(function(){try{jQuery(d).remove(),jQuery(c).remove()}catch(b){jQuery.handleError(a,h,null,b)}},100),h=null}};a.timeout>0&&setTimeout(function(){g||i("timeout")},a.timeout);try{var c=jQuery("#"+f);jQuery(c).attr("action",a.url),jQuery(c).attr("method","POST"),jQuery(c).attr("target",e),c.encoding?jQuery(c).attr("encoding","multipart/form-data"):jQuery(c).attr("enctype","multipart/form-data"),jQuery(c).submit()}catch(j){jQuery.handleError(a,h,null,j)}return jQuery("#"+e).load(i),{abort:function(){}}},uploadHttpData:function(r,type){var data=!type;return data=type=="xml"||data?r.responseXML:r.responseText,type=="script"&&jQuery.globalEval(data),type=="json"&&eval("data = "+data),type=="html"&&jQuery("<div>").html(data).evalScripts(),data},handleError:function(){}});
(function(a){"use strict";var b=function(a,c,d){var e=d.img||document.createElement("img"),f,g;return e.onerror=c,e.onload=function(){g&&b.revokeObjectURL(g),c(b.scale(e,d))},window.Blob&&a instanceof Blob||window.File&&a instanceof File?f=g=b.createObjectURL(a):f=a,f?(e.src=f,e):b.readFile(a,function(a){e.src=a})},c=window.createObjectURL&&window||window.URL&&URL||window.webkitURL&&webkitURL;b.scale=function(a,b){b=b||{};var c=document.createElement("canvas"),d=a.width,e=a.height,f=Math.max((b.minWidth||d)/d,(b.minHeight||e)/e);return f>1&&(d=parseInt(d*f,10),e=parseInt(e*f,10)),f=Math.min((b.maxWidth||d)/d,(b.maxHeight||e)/e),f<1&&(d=parseInt(d*f,10),e=parseInt(e*f,10)),a.getContext||b.canvas&&c.getContext?(c.width=d,c.height=e,c.getContext("2d").drawImage(a,0,0,d,e),c):(a.width=d,a.height=e,a)},b.createObjectURL=function(a){return c?c.createObjectURL(a):!1},b.revokeObjectURL=function(a){return c?c.revokeObjectURL(a):!1},b.readFile=function(a,b){if(window.FileReader&&FileReader.prototype.readAsDataURL){var c=new FileReader;return c.onload=function(a){b(a.target.result)},c.readAsDataURL(a),c}return!1},typeof define!="undefined"&&define.amd?define(function(){return b}):a.loadImage=b})(window);