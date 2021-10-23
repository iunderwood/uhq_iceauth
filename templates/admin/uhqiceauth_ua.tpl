<{if $data.uacount > 0}>
<p>
    <{$data.uacount}>
    <{if $data.uacount > 1}>
    <{$smarty.const._AM_UHQICEAUTH_UABAN_PLU}>
    <{else}>
    <{$smarty.const._AM_UHQICEAUTH_UABAN_ONE}>
    <{/if}>
</p>
<table>
    <tr>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_HASH}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_USERAGENT}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_ACT}></th>
    </tr>
    <{foreach item=list from=$data.uadata.list}>
    <tr class="<{cycle values=" odd,even
    "}>">
    <td><{$list.sequence}></td>
    <td nowrap><{$list.useragent}></td>
    <td>
        <a href='ua.php?op=delete&sequence=<{$list.sequence}>' title='<{$smarty.const._AM_UHQICEAUTH_LIST_MDEL}>'>
            <img src='<{xoModuleIcons16 delete.png}>' alt='<{$smarty.const._AM_UHQICEAUTH_LIST_MDEL}>'></a>
    </td>
    </tr>
    <{/foreach}>
</table>
<{else}>
<{$smarty.const._AM_UHQICEAUTH_UABAN_NONE}><br>
<{/if}>
<br>
<p><a href='ua.php?op=insert'><{$smarty.const._AM_UHQICEAUTH_ADDUA}></a></p>
<br>
<hr>
<br>
<h4><{$smarty.const._AM_UHQICEAUTH_UABAN_TEST}></h4>
<form action="ua.php" method="post">
    <input type=hidden name="op" value="testua">
    <input type=text name="testua">
    <input type=submit value="Query UA">
</form>
