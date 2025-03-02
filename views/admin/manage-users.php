<?php     
    include_once '../../config.php';
    include_once '../../components/admin-header.php';
?>
<h2 class="p-2 text-center">Manage Users</h2>
    <table class="table table-hover m-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Published Posts</th>
                <th>Drafts</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $sql = "SELECT 
                    u.*, 
                    COUNT(CASE WHEN b.status = 'pending' THEN 1 END) AS pending_blogs,
                    COUNT(CASE WHEN b.status = 'published' THEN 1 END) AS published_blogs
                    FROM users u
                    LEFT JOIN blog b ON u.username = b.username
                    GROUP BY u.username";
                
            $result = $mysqli->query($sql);
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['published_blogs']}</td>
                            <td>{$row['pending_blogs']}</td>
                            <td>
                            ";
                            if($row['username'] !== 'admin' && $row['username'] !== 'Admin'){ 
                                if($row['status'] === 'Active'){
                                    echo "<a href='../../logic/disable.php?username={$row['username']}' class='btn btn-danger'>Disable</a>";
                                }
                                else
                                echo "<a href='../../logic/enable.php?username={$row['username']}' class='btn btn-primary'>Enable</a>";
                            }
                            else{  //if user is admin, then there won't be an option for disabling
                                echo"</td>
                                </tr>";
                            }
                            echo"</td>
                        </tr>";
                }
            }
        ?>
        </tbody>
    </table>
    <?php if (isset($_SESSION['message'])): ?>
    <script>
        alert("<?php echo $_SESSION['message']; ?>");
    </script>
    <?php 
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    ?>
<?php endif; ?>
    <?php     
    include_once '../../components/guest-footer.php';
?>