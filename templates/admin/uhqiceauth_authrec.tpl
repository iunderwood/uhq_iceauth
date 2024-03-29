<{if $error|default:''}>
<span style="color: red; "><{$smarty.const._AM_UHQICEAUTH_SQLERR}></span>: <{$error}>
<{elseif $data}>
<p>
    <{if $data.aucount == 0}><{$smarty.const._AM_UHQICEAUTH_AUTH_NONE}>
    <{elseif $data.aucount == 1}><{$data.aucount}><{$smarty.const._AM_UHQICEAUTH_AUTH_ONE}>
    <{elseif $data.aucount > 1}><{$data.aucount}><{$smarty.const._AM_UHQICEAUTH_AUTH_PLU}>
    <{else}><{$smarty.const._AM_UHQICEAUTH_SQLERR}>
    <{/if}>
</p>

<{if $data.aucount > 0}>
<{foreach item=recordlist from=$data.rec}>
<br>
<h4>
    <{if $recordlist.count > 0}>
    <{if $recordlist.order == "DESC"}>
    <{$smarty.const._AM_UHQICEAUTH_STATS_LAST}>
    <{else}>
    <{$marty.const._AM_UHQICEAUTH_STATS_FIRST}>
    <{/if}>
    &nbsp;<{$recordlist.limit}>&nbsp;
    <{else}>
    <{$smarty.const._AM_UHQICEAUTH_STATS_NOREC}>
    <{/if}>

    <{if $recordlist.type == "L"}>
    <{$smarty.const._AM_UHQICEAUTH_STATS_LCREC}>
    <{elseif $recordlist.type == "S"}>
    <{$smarty.const._AM_UHQICEAUTH_STATS_SCREC}>
    <{elseif $recordlist.type == "A"}>
    <{$smarty.const._AM_UHQICEAUTH_STATS_AUREC}>
    <{/if}>
</h4>

<{if $recordlist.count > 0}>
<table>
    <tr>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_HASH}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_TIME}></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_SVR}></th>
        <th></th>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_LOG}></th>
        <{if $data.usegeo}>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_GEO}></th>
        <{/if}>
        <th><{$smarty.const._AM_UHQICEAUTH_LIST_REFERER}></th>
    </tr>
    <{foreach item=list from=$recordlist.record}>
    <tr class="<{cycle values=" odd,even
    "}>">
    <td><a href='authrec.php?op=authrecord&sequence=<{$list.sequence}>'><{$list.sequence}></a></td>
    <td nowrap><{$list.logtime}></td>
    <td><{$list.server}>:<{$list.port}><{$list.mount}></td>
    <td>
        <{if $list.username}>
        <{$list.username}>
        <{else}>
        <{$smarty.const._AM_UHQICEAUTH_AUTH_ANON}>
        <{/if}>
    </td>
    <td>

        <{if $recordlist.type == "S"}>
        <{if $list.authstat == "P"}>
        <{$smarty.const._AM_UHQICEAUTH_AUTH_SCPASS}>
        <{else}>
        <{$smarty.const._AM_UHQICEAUTH_AUTH_SCFAIL}>
        <{/if}>
        <{$list.userip}>
        <{if $list.authinfo}>&nbsp;-&nbsp;<i>[<{$list.authinfo}>]</i><{/if}>
        <{elseif $recordlist.type == "L"}>
        <{if $list.authstat == "P"}>
        <{$smarty.const._AM_UHQICEAUTH_AUTH_SCPASS}>
        <{else}>
        <{$smarty.const._AM_UHQICEAUTH_AUTH_SCFAIL}>
        <{/if}>
        <{$list.userip}>
        <{if $list.authinfo}>&nbsp;-&nbsp;<i>[<{$list.authinfo}>]</i><{/if}>
        <{if $list.duration != ''}>&nbsp;-&nbsp;<{$list.duration}>s<{/if}>
        <{elseif $recordlist.type == "A"}>
        <{if $list.authstat == "P"}>
        <{$smarty.const._AM_UHQICEAUTH_AUTH_AUPASS}>
        <{else}>
        <{$smarty.const._AM_UHQICEAUTH_AUTH_AUFAIL}>
        <{/if}>
        <{$list.userip}>
        <{if $list.authinfo}>&nbsp;-&nbsp;<i>[<{$list.authinfo}>]</i><{/if}>
        <{/if}>
    </td>
    <{if $data.usegeo}>
    <td>
        <{if $list.flag}>
        <img src="/modules/uhqgeolocate/images/flags/<{$list.flag}>.gif" title="<{$list.geocc}>">
        <{else}>
        <{$list.geocc}>
        <{/if}>
        <{if $list.ccname}><{$list.ccname}><{/if}>
    </td>
    <{/if}>
    <td>
        <{if $list.referer}>
        <{$list.referer}>
        <{else}><span style="color: green; ">-</span>
        <{/if}>
    </td>
    </tr>
    <{/foreach}>
</table>
<{/if}>
<{/foreach}>
<{/if}>

<{/if}>
