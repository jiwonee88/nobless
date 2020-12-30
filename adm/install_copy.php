<?php
$sub_menu = "800100";
include_once("./_common.php");

auth_check($auth[$sub_menu], 'w');

$g5['title'] = '채용 복사';
include_once(G5_PATH.'/head.sub.php');

$data=get_recruit($rt_no);
?>

<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo G5_JS_VER; ?>"></script>

<div class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <form name="frecruitcopy" id="frecruitcopy" action="./recruit_copy_update.php" onsubmit="return frecruitcopy_check(this);" method="post">
    <input type="hidden" name="rt_no" value="<?php echo $rt_no ?>" id="rt_no">
    <input type="hidden" name="token" value="">
    <div class=" new_win_con">
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <caption><?php echo $g5['title']; ?></caption>
            <tbody>
            <tr>
                <th scope="col">원본 채용</th>
                <td><?php echo $data['rt_name'] ?> [<?php echo $data['rt_company']?>]</td>
            </tr>
            <tr>
                <th scope="col"><label for="rt_name">복사 채용명</label></th>
                <td><input name="rt_name" type="text" required=required class="required frm_input" id="rt_name" value="[복사본] <?php echo $data['rt_name'] ?>" size="50"></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
    <div class="win_btn ">
        <input type="submit" class="btn_submit btn" value="복사">
        <input type="button" class="btn_close btn" value="창닫기" onclick="window.close();">
    </div>

    </form>

</div>

<script>
function frecruitcopy_check(f)
{

    return true;
}
</script>


<?php
include_once(G5_PATH.'/tail.sub.php');
?>
