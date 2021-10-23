<{if $block.error}>
<{$block.error}>
<{else}>
<{if $block.mount}>
<table>
    <tr>
        <th>Server</th>
        <th>Port</th>
        <th>Mount</th>
    </tr>
    <{foreach item=list from=$block.mount}>
    <tr>
        <td><{$list.server}></td>
        <td><{$list.port}></td>
        <td><{$list.mount}></td>
    </tr>
    <{/foreach}>
</table>
<{else}>
No Active Mountpoints.
<{/if}>
<{/if}>
