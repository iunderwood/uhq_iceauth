<{if $error|default:''}>
<span style="color: red; "><{$smarty.const._AM_UHQICEAUTH_SQLERR}></span>: <{$error}>
<{elseif $data}>

<p>
    <{if $data.servercount == 0}><{$smarty.const._AM_UHQICEAUTH_SVR_NONE}>
    <{elseif $data.servercount == 1}><{$data.servercount}><{$smarty.const._AM_UHQICEAUTH_SVR_ONE}>
    <{elseif $data.servercount > 1}><{$data.servercount}><{$smarty.const._AM_UHQICEAUTH_SVR_PLU}>
    <{else}><{$smarty.const._AM_UHQICEAUTH_SQLERR}>
    <{/if}>
</p>

<{/if}>

<{if $data.servercount > 0}>

<table>
    <tr>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_SVR}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_PORT}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_MOUNT}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_INTROS}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_TIMEL}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_LAUTH}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_SAUTH}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_HITS}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_ACT}></th>
    <tr>

        <{foreach item=list from=$data.mounts}>
    <tr class="<{cycle values=" odd,even
    "}>">
    <td><{$list.server}></td>
    <td><{$list.port}></td>
    <td><{$list.mount}></td>

    <td>
        <{if $list.intro}>
        <{foreach item=ilist from=$list.intro}>
        <{$ilist.sequence}> - <{$ilist.filename}> - <a href=
                                                               "mountpoints.php?op=delintro&intronum=<{$ilist.intronum}>&server=<{$list.server}>&port=<{$list.port}>&mount=<{$list.mount}>">
        <{$smarty.const._AM_UHQICEAUTH_LIST_REMOVE}></a>
        <{/foreach}>
        <{else}>
        <{$smarty.const._AM_UHQICEAUTH_NOINTROS}>
        <{/if}>
    </td>

    <td>
        <{if $list.timelimit > 0 }>
        <{$list.timelimit}> sec
        <{else}>
        <{$smarty.const._AM_UHQICEAUTH_NOLIMIT}>
        <{/if}>
    </td>

    <td>
        <{if $list.lst_auth_typ == "A"}><{$smarty.const._AM_UHQICEAUTH_FORM_AUTHA}>
        <{elseif $list.lst_auth_typ == "D"}><{$smarty.const._AM_UHQICEAUTH_FORM_AUTHD}>
        <{else}><{$smarty.const._AM_UHQICEAUTH_UNDEF}><{$list.lst_auth_typ}><{/if}>
    </td>

    <td>
        <{if $list.src_auth_typ == "S"}><{$list.src_auth_un}> / <{$list.src_auth_pw}>
        <{elseif $list.src_auth_typ == "A"}><{$smarty.const._AM_UHQICEAUTH_FORM_AUTHA}>
        <{elseif $list.src_auth_typ == "N"}><{$smarty.const._AM_UHQICEAUTH_FORM_AUTHN}>
        <{else}><{$smarty.const._AM_UHQICEAUTH_UNDEF}><{$list.src_auth_typ}><{/if}>
    </td>

    <td>L:<span style="color: green; "><{$list.hits_pass}></span>/<span style="color: red; "><{$list.hits_fail}></span>
        <br>S:<span style="color: green; "><{$list.src_hits_pass}></span>/<span style="color: red; "><{$list.src_hits_fail}></span>
    </td>

    <td>
        <a href='mountpoints.php?op=edit&server=<{$list.server}>&port=<{$list.port}>&mount=<{$list.mount}>'
           title='<{$smarty.const._AM_UHQICEAUTH_LIST_MEDIT}>'>
            <img src='<{xoModuleIcons16 edit.png}>' alt='<{$smarty.const._AM_UHQICEAUTH_LIST_MEDIT}>'></a>&nbsp;

        <a href='mountpoints.php?op=delete&server=<{$list.server}>&port=<{$list.port}>&mount=<{$list.mount}>'
           title='<{$smarty.const._AM_UHQICEAUTH_LIST_MDEL}>'>
            <img src='<{xoModuleIcons16 delete.png}>' alt='<{$smarty.const._AM_UHQICEAUTH_LIST_MDEL}>'></a>&nbsp;

        <a href='mountpoints.php?op=reset&server=<{$list.server}>&port=<{$list.port}>&mount=<{$list.mount}>'
           title='<{$smarty.const._AM_UHQICEAUTH_LIST_MRESET}>'>
            <img src='<{xoModuleIcons32 synchronized.png}>' width=16 height=16
                 alt='<{$smarty.const._AM_UHQICEAUTH_LIST_MRESET}>'></a>&nbsp;

        <a href='mountpoints.php?op=addintro&server=<{$list.server}>&port=<{$list.port}>&mount=<{$list.mount}>'
           title='<{$smarty.const._AM_UHQICEAUTH_LIST_MINTRO}>'>
            <img src='<{xoModuleIcons16 add.png}>' alt='<{$smarty.const._AM_UHQICEAUTH_LIST_MINTRO}>'></a>
    </td>

    </tr>
    <{/foreach}>
</table>
<{/if}>
