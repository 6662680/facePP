/**
 * Created by LatteCake on 15/5/17.
 */
$(function(){
    $('#uploadFile').change(function()
    {
        loadingMask.show();
        $.ajaxFileUpload({
            fileElementId: 'uploadFile',
            url: baseUrl + 'uploadImage',
            dataType: 'json',
            beforeSend: function (XMLHttpRequest) {
                //("loading");
                console.log(XMLHttpRequest);
            },
            success: function (data, textStatus) {
                if( data.success )
                {
                    window.location.href = data.data.redirect;
                }else
                {
                    sweetAlert("Oops...", data.message, "error");
                    window.location.reload();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var img = "图片上传失败！";
                sweetAlert("Oops...", img, "error");
                loadingMask.hide();
            },
            complete: function (XMLHttpRequest, textStatus) {
                //("loaded");
                if( textStatus == 'error' )
                {
                    swal({
                        title: "错误?",
                        text: "请上传宽度小于1000像素的屁股...",
                        type: "error",
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "好的...",
                        closeOnConfirm: false
                    }, function(){
                        window.location.reload();
                    });
                }
                loadingMask.hide();
            }
        });
    });
    $(window).resize(function()
    {
        loadingMask.setMaskBoxSize();
    });
});

var main = {

    callOutInfoBox: $('#callOutInfo'),

    loadedImageInfo: function( imageId )
    {
        var me = this;
        loadingMask.show();
        $.get( baseUrl + 'imageInfo/' + imageId, function( response )
        {
            if( response.success )
            {
                main.setImageInfo( response.data );
                me.callOutInfoBox.hide();
            }else
            {
                me.callOutInfoBox.show();
                me.callOutInfoBox.find('h4').text('错误');
                me.callOutInfoBox.find('span').text(response.message);
            }
            loadingMask.hide();
        }, 'json');
    },

    setImageInfo: function( response )
    {
        var tableHtml = '';
        $.each( response.faceInfo, function(key, item){
            var attribute = item.attribute,
                gender = 'male';
            if( attribute.gender.value == 'Female' )
                gender = 'female';

            tableHtml += '<tr> <th scope="row">'+ key +'</th> ' +
            '<td>' + gender + '</td> ' +
            '<td>' + attribute.age.value + '</td>' +
            '<td>' + attribute.glass.value + '</td></tr>';
        });
        $('#table-list').html( tableHtml );
        this.setImageFace( response.faceInfo );
    },

    setImageFace: function( imageList )
    {
        var faceBox = '';
        $.each(imageList, function(key, item)
        {
            var positoin  = item.position,
                attribute = item.attribute;
            var faceTooltipWidth  = Math.ceil(imageWidth * (positoin.width / 100)),
                faceTooltipHeight = Math.ceil(imageHeight * (positoin.height / 100)),
                faceTooltipLeft   = Math.ceil(imageWidth * (positoin.center.x / 100) - (faceTooltipWidth / 2)),
                faceTooltipTop    = Math.ceil(imageWidth * (positoin.center.y / 100) / 2 + ( faceTooltipHeight / 2 ) ),
                tooltipTop        = faceTooltipTop - 100,
                tooltipLeft       = Math.ceil(faceTooltipLeft + (faceTooltipWidth / 2)) - 42;

            var gender = 'male';
            if( attribute.gender.value == 'Female' )
                gender = 'female';

            faceBox += '<div data-html="true" class="child face-tooltip big-face-tooltip " id="rect' + item.face_id + '" style="left: '+ faceTooltipLeft +'px; top: '+ faceTooltipTop +'px; width: '+ faceTooltipWidth +'px; height: '+ faceTooltipHeight +'px; border: 1px solid white; position: absolute;" data-original-title="" title="" aria-describedby="tooltip'+ item.face_id +'"></div>' +
                '<div class="tooltip fade top in" role="tooltip" id="tooltip'+ item.face_id +'" style="top:'+ tooltipTop +'px; left: '+ tooltipLeft +'px; display: block;">' +
                '<div class="tooltip-arrow" style="left: 50%;"></div>' +
                '<div class="tooltip-inner"> <div>' +
                '<span><img src="http://storage.lattecake.com/static/images/icons/icon-gender-'+ gender +'.png" class="big-face-tooltip"></span>' + attribute.age.value +
                '</div></div></div>';
        });
        $('#faces').append(faceBox);
    }
};

var loadingMask = {
    maskBox: $('#loading-mask'),
    show: function()
    {
        this.setMaskBoxSize();
        this.maskBox.show();
    },
    hide: function()
    {
        this.maskBox.hide();
    },
    setMaskBoxSize: function()
    {
        var width = $(window).width(),
            height = $(window).height();
        this.maskBox.css({
            width: width + 'px',
            height: height + 'px',
            position: 'fixed',
            top: 0,
            left: 0
        });
    }
};