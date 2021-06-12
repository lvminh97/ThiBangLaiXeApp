<html>
    <body>
        <table>
            <thead>
                <tr>
                    <th width="30px">No</th>
                    <th width="15px">ID</th>
                    <th width="200px">Image</th>
                    <th width="300px">Ques</th>
                    <th width="250px">Answer</th>
                    <th width="100px">Correct</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach($viewParams['data'] as $data){ 
                $i++; ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><b><?php echo $data['id']."<br>".$data['type'] ?></b></td>
                    <td><img src="./Resource/Images/<?php echo $data['ques_image'] ?>" style="width: 100%; height: auto;"></td>
                    <td><?php echo $data['ques'] ?></td>
                    <td style="padding-top: 10px; padding-bottom: 15px"><?php echo $data['ans1']."<br>".$data['ans2']."<br>".$data['ans3']."<br>".$data['ans4'] ?></td>
                    <td><?php echo $data['correct_ans'] ?></td>
                </tr>
            <?php 
            } ?>
            </tbody>
        </table>
    </body>
</html>