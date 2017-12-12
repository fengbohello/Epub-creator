<?php
   header('content-Type:text/html;charset=utf-8');

   require(dirname(__FILE__) . "/../Epub/Epub.php");

   require('bookdata.php');
   $epub = new EpubPacker('./book002');

   //初始化Epub，可在此之前初始化公有属性
   $epub->init();

   //设置书籍的主要信息【书名、作者...】
   $epub->setBookInfo($bookInfo);

   //制作epub的封面
   $epub->makeCover($bookCover);

   //章节中的图片定位
   $imgLocation = './';

   $i = 1;
   foreach( $data as $chapterTitle => $chapterBody )
   {
      $chapterBody = $epub->bodyFilter($chapterBody, $imgLocation);
      $epub->setData($chapterTitle,$chapterBody,$i);
      $chapterFileName = $epub->saveChapter();
      if( $chapterFileName )
      {
         $epub->addChapter();
         $i++;
      }
   }

   $pack = $epub->saveBook('./','ebook.epub');

   echo $epub->showError();

   if( $pack )
   {
      echo "epub书籍打包成功！\n";
   }else{
      echo "epub书籍打包失败！\n";
   }

?>
