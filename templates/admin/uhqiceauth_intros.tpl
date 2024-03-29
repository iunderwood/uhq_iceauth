<{if $error|default:''}>
<p><span style="color: red; "><{$smarty.const._AM_UHQICEAUTH_SQLERR}></span>: <{$error}></p>
<{elseif $data}>
<{if $data.incount > 0}>
<script type="text/javascript">
    <!--
    function uhq_popup(linkurl, popw, poph) {
        if (!window.focus) return true;
        var href;
        if (typeof(linkurl) == 'string')
            href = linkurl;
        else
            href = linkurl.href;
        window.open(href, 'uhqiceauth_play', 'width=' + popw + ',height=' + poph + ',scrollbars=yes');
        return false;
    }
    //-->
</script>

<p>
    <{$data.incount}>
    <{if $data.incount > 1}>
    <{$smarty.const._AM_UHQICEAUTH_INTROS_PLU}>
    <{else}>
    <{$smarty.const._AM_UHQICEAUTH_INTROS_ONE}>
    <{/if}>
</p>

<table>
    <tr>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_HASH}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_LFILE}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_CODEC}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_DESC}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_ACT}></th>
    </tr>
    <{foreach item=list from=$data.intros}>
    <tr class="<{cycle values=" odd,even
    "}>">
    <td><{$list.intronum}></td>
    <td>/modules/uhqiceauth/intros/<{$list.filename}></td>
    <td>
        <{if $list.codec == "O"}><{$smarty.const._AM_UHQICEAUTH_OGG}>
        <{elseif $list.codec == "M"}><{$smarty.const._AM_UHQICEAUTH_MP3}>
        <{elseif $list.codec == "A"}><{$smarty.const._AM_UHQICEAUTH_AAC}>
        <{elseif $list.codec == "P"}><{$smarty.const._AM_UHQICEAUTH_AACPLUS}>
        <{/if}>
    </td>
    <td><{$list.description}></td>
    <td>
        <a href='intros.php?op=edit&intronum=<{$list.intronum}>'><{$smarty.const._AM_UHQICEAUTH_LIST_ACT_EDIT}></a> -
        <a href='intros.php?op=delete&intronum=<{$list.intronum}>'><{$smarty.const._AM_UHQICEAUTH_LIST_ACT_DEL}></a> -
        <a href='intros.php?op=play&intronum=<{$list.intronum}>' onClick='return uhq_popup(this,380,100)'><{$smarty.const._AM_UHQICEAUTH_LIST_ACT_PLAY}>

    </td>
    </tr>
    <{/foreach}>
</table>
<{else}>
<p><{$smarty.const._AM_UHQICEAUTH_INTROS_NONE}></p>
<{/if}>
<{/if}>
