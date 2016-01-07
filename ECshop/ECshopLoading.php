<script type="text/javascript">
//页面加载事件初始化  jquery_mobile 自带加载
$( document ).on( "click", ".show-page-loading-msg", function() {
    var $this = $( this ),
        theme = $this.jqmData( "theme" ) || $.mobile.loader.prototype.options.theme,
        msgText = $this.jqmData( "msgtext" ) || $.mobile.loader.prototype.options.text,
        textVisible = $this.jqmData( "textvisible" ) || $.mobile.loader.prototype.options.textVisible,
        textonly = !!$this.jqmData( "textonly" );
        html = $this.jqmData( "html" ) || "";
    $.mobile.loading( "show", {
            text: msgText,
            textVisible: textVisible,
            theme: theme,
            textonly: textonly,
            html: html
    });
})
.on( "click", ".hide-page-loading-msg", function() {
    $.mobile.loading( "hide" );
});
</script>

    <!--jquery-mobile加载事件控制开始-->
    <button class="show-page-loading-msg" data-textonly="false" data-textvisible="false" data-msgtext="" data-inline="true" style="display:none;"></button>
    <button class="hide-page-loading-msg" data-inline="true" data-icon="delete" style="display:none;"></button>
    <!--jquery-mobile加载事件控制结束-->