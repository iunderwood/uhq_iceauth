<h3><{$smarty.const._AM_UHQICEAUTH_DEL_VER}></h3>
<p><{$data.mount.server}>:<{$data.mount.port}><{$data.mount.mount}></p>
<br>
<h4><{$smarty.const._AM_UHQICEAUTH_DEL_HIST}></h4>

<p><{$data.aucount}>
    <{if $data.aucount == 1}>
    <{$smarty.const._AM_UHQICEAUTH_AUTH_ONE}>
    <{else}>
    <{$smarty.const._AM_UHQICEAUTH_AUTH_PLU}>
    <{/if}>
</p>

<p><{$data.mlcount}>
    <{if $data.mlcount == 1}>
    <{$smarty.const._AM_UHQICEAUTH_MOUNTS_ONE}>
    <{else}>
    <{$smarty.const._AM_UHQICEAUTH_MOUNTS_PLU}>
    <{/if}>
</p>
