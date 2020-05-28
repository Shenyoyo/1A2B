<?php

session_start();
/*
 * 用來產生 4 個不重複的數字
 */
function generateAnswer() 
{
    $numberStack = range(0, 9);
    shuffle($numberStack); //打亂數字
    $answer = '';
    for($i = 0; $i < 4; $i++) {
        $answer .= $numberStack[$i]; 
    }
    return $answer;
}

if (empty($_SESSION['answer'])) {
    /*
     * 如果答案不存在就產生一個
     */
    $_SESSION['answer'] = generateAnswer();
}
$duplicate = false;
$numberDuplicate = false;

if (!empty($_POST['answer'])) {
    if(!empty($_SESSION['duplicate'])){
        if(in_array($_POST['answer'],$_SESSION['duplicate'])){
            $duplicate = true;
        } else {
            $duplicate = false;
        }
    }
    $_SESSION['duplicate'][] = $_POST['answer'];
    $reset = false;
    if ($_POST['answer'] == $_SESSION['answer']) {
        $reset = true;//重設答案與記錄
        echo '<h3>恭喜猜對了，答案就是 ' . $_POST['answer'] . ' ，你猜了' . (count($_SESSION['history']) + 1) . '次</h3>';
    } else {
        if(!$duplicate) {
        $counterA = $counterB = 0;
        for ($i = 0; $i < 4; $i++) {
            $postAnswer[] = substr($_POST['answer'], $i, 1); //將回答的數字轉成陣列
            if(substr($_POST['answer'], $i, 1) == substr($_SESSION['answer'], $i, 1)) {
                ++$counterA;
            } elseif(false !== strpos($_SESSION['answer'], substr($_POST['answer'], $i, 1))) {
                ++$counterB;
            }
        }
        /*
         * 檢查輸入的答案是否有重複數字
         */
        if(count($postAnswer) != count(array_unique($postAnswer))) {
            $numberDuplicate = true;
        }
        if(!$numberDuplicate) {
        $_SESSION['history'][] = array($_POST['answer'], $counterA . 'A' . $counterB . 'B');
        echo '<h3>這次提供的數字為 ' . $_POST['answer'] . ' ，結果是 ' . $counterA . 'A' . $counterB . 'B</h3> ';
        //echo '答案是：' . $_SESSION['answer'];
        } else {
            echo '<h3>輸入答案數字重複，請重新輸入，謝謝。<h3>';
        }
        } else {
            echo '<h3>輸入重複的答案，請重新輸入，謝謝。<h3>';
        }
        
    }
    if (!empty($_SESSION['history'])) {
        
        echo '<ul>';
        foreach ($_SESSION['history'] AS $history) {
            echo '<li>' . $history[0] . ' => ' . $history[1] . '</li>';
        }
        echo '</ul>';
    }
    if ($reset) {
        unset($_SESSION['']);
        unset($_SESSION['history']);
        unset($_SESSION['duplicate']);
        $_SESSION['answer'] = generateAnswer();
    }
}