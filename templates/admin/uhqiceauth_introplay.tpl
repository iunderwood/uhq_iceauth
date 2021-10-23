<{if $error|default:''}>

<span style="color: red; "><{$smarty.const._AM_UHQICEAUTH_INTROS_PLAY_ERROR}></span>: <{$error}>

<{elseif $data}>

<style>
    * {
        font-family: Trebuchet Ms, Arial, serif;
        font-size: 12px;
    }

    select,
    input {
        border: 1px solid silver;
    }
</style>

<script language="javascript" src="/modules/uhqiceauth/includes/VLCcontrols/ExternalLibLoader.js"></script>
<script language="javascript" src="/modules/uhqiceauth/includes/VLCcontrols/VLCobject.js"></script>
<script type="text/javascript" src="/modules/uhqiceauth/includes/VLCcontrols/VLCcontrols.js"></script>

<div style="text-align: center;">Listening to: <i><{$data.filename}></i></div>
<div id="player">
    <span style="color: red; "><{$smarty.const._AM_UHQICEAUTH_INTROS_PLAY_ERROR}></span>:
    <{$smarty.const._AM_UHQICEAUTH_INTROS_PLAY_VLCREQ}>
</div>

<script type="text/javascript">
    var myvlc = null;
    var myvlc_ctrl = null;

    myvlc = new VLCObject("mymovie", "365", "0");
    myvlc.addParam("MRL", "<{$data.playurl}>");
    myvlc.write("player");

    myvlc_ctrl = new VLCcontrols(myvlc);
</script>

<{/if}>
