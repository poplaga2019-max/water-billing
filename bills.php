<table border="1" cellpadding="5">
<tr>
    <th>ชื่อ</th>
    <th>หน่วยใช้</th>
    <th>จำนวนเงิน</th>
    <th>สถานะ</th>
    <th>จัดการ</th>
</tr>

<?php while($row = $res->fetch_assoc()){ ?>
<tr>
    <td><?= $row['name'] ?></td>
    <td><?= $row['used_unit'] ?></td>
    <td><?= $row['amount'] ?> บาท</td>
    <td>
        <?php if($row['status']=='paid'){ ?>
            <span style="color:green;">จ่ายแล้ว</span>
        <?php }else{ ?>
            <span style="color:red;">ยังไม่จ่าย</span>
        <?php } ?>
    </td>
    <td>
        <?php if($row['status']!='paid'){ ?>
            <a href="pay.php?id=<?= $row['id'] ?>">รับเงิน</a>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>    </table>

</body>
</html>
