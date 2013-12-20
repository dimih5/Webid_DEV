<div class="row">
    <div class="span3">
        <div class="well" style="max-width: 340px; padding: 8px 0;">
            <ul class="nav nav-list">
                <li class="nav-header">{L_205}</li>
                <li><a href="{SITEURL}user_menu.php?cptab=summary">{L_25_0080}</a></li>
                <li class="nav-header">{L_25_0081}</li>
                <li><a href="#">{L_621}</a></li>
                <li class="active"><a href="edit_usergroups.php">{L_5510}</a></li>
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
    
    <div class="span9">
        <h1>{NAME}</h1>

        <form action="{SITEURL}edit_usergroup.php" method="post" id="edit_usergroup">
            <input type="hidden" name="id" value="{ID}">        
            <input type="hidden" name="csrftoken" value="{_CSRFTOKEN}">
            <input type="hidden" name="action" value="save">
            <table class="table table-striped" id="usertable">
                <thead>
                    <tr>
                        <th style="width: 100px;"></th>
                        <th>Company</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Country</th>        
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN users -->
                    <tr>
                        <td><input type="checkbox" name="users[]" value="{users.ID}" {users.CHECKED}></td>
                        <td>{users.COMPANY}</td>
                        <td>{users.NAME}</td>
                        <td>{users.EMAIL}</td>
                        <td>{users.COUNTRY}</td>
                    </tr>
                    <!-- END users -->
                </tbody>
            </table>
            
            <button type="button" class="btn" onclick="javascript: submitform('edit_usergroup')">Submit</button>
        </form>
    </div>
    
</div>

<script type="text/javascript">
    
    var oTable;
    
    $(document).ready(function() {
        oTable = $('#usertable').dataTable( {
            "sDom": "<'row'<'span9'l><'span9'f>r>t<'row'<'span9'i><'span9'p>>",
            "aoColumnDefs" : [ {'bSortable' : false,'aTargets' : [ 0 ]} ] // disable sorting on checkboxes
        } );
    } );
    
    // This function will gather all checkbox data from all pages before submitting
    function submitform(id) {
        var form = $('#'+id);
        
        // Append data to the form, hidden
        $('input:checked', oTable.fnGetNodes()).each(function() {
            $(this).css('display', 'none');
            form.append($(this));
        });
        
        // Submit form now that it has all fields
        form.submit();
    }
</script>