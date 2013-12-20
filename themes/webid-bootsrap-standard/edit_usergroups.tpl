<div class="row">
    <div class="span3">
        <div class="well" style="max-width: 340px; padding: 8px 0;">
            <ul class="nav nav-list">
                <li class="nav-header">{L_205}</li>
                <li><a href="{SITEURL}user_menu.php?cptab=summary">{L_25_0080}</a></li>
                <li class="nav-header">{L_25_0081}</li>
                <li><a href="edit_data.php">{L_621}</a></li>
                <li class="active"><a href="#">{L_5510}</a></li>
                <li><a href="yourfeedback.php">{L_208}</a></li>
                <li><a href="buysellnofeedback.php">{L_207}</a> <small><span class="muted">{FBTOLEAVE}</span></small></li>
                <li><a href="mail.php">{L_623}</a> <small><span class="muted">{NEWMESSAGES}</span></small></li>
                <li><a href="outstanding.php">{L_422}</a></li>
                <li class="important"><a href="contents.php?show=rules">{L_P7}</a></li>
                <!-- IF B_CAN_SELL -->
                <li class="nav-header">{L_25_0082}</li>
                <li><a href="select_category.php">{L_028}</a></li>
                <li><a href="selleremails.php">{L_25_0188}</a></li>
                <li><a href="yourauctions_p.php">{L_25_0115}</a></li>
                <li><a href="yourauctions.php">{L_203}</a></li>
                <li><a href="yourauctions_c.php">{L_204}</a></li>
                <li><a href="yourauctions_s.php">{L_2__0056}</a></li>
                <li><a href="yourauctions_sold.php">{L_25_0119}</a></li>
                <li><a href="selling.php">{L_453}</a> </li>
                <!-- ENDIF -->
                <li class="nav-header">{L_25_0083}</li>
                <li><a href="auction_watch.php">{L_471}</a></li>
                <li><a href="item_watch.php">{L_472}</a></li>
                <li><a href="yourbids.php">{L_620}</a></li>
                <li><a href="buying.php">{L_454}</a></li>
            </ul>
        </div>
    </div>

    <!-- IF B_FLASH_MSG -->
    <div class="span9 flash-msg {FLASH_MSG_CLASS}">
        <p>{FLASH_MSG}</p>
    </div>
    <!-- ENDIF -->
    
    <div class="span9">
        <div style="margin: 10px;">
            <a href="{SITEURL}create_usergroup.php"><button class="btn" type="button">New group</button></a>
        </div>
        <p>
            <b>Notice: Active auctions will ignore changes to groups.</b>
        </p>
        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <th style="width: 100px;"></th>
            </tr>
            
            <!-- BEGIN usergroups -->
            <tr>
                <td>{usergroups.NAME} ({usergroups.USERCOUNT})</td>
                <td>
                    <a href="{SITEURL}edit_usergroup.php?id={usergroups.ID}" alt="edit"><i class="icon-edit"></i></a>
                    <a href="#" id="{usergroups.ID}" alt="delete" onclick="javascript: deleteGroup(this);"><i class="icon-remove"></i></a>
                </td>
            </tr>
            <!-- END usergroups -->
            
        </table>
    </div>
    
</div>

<script type="text/javascript">
    function deleteGroup(el) {
        var id = $(el).attr('id');
        if(confirm('Are you sure you want to delete this group?')) {
            var data = {
                'action': 'delete',
                'id': id,
                "csrftoken": "{_CSRFTOKEN}"
            };
            $.post( "{SITEURL}edit_usergroups.php", data, function() {
                location.reload();
            });
        }
    }
</script>



