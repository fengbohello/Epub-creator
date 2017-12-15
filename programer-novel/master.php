<?php
   header('content-Type:text/html;charset=utf-8');
   require(dirname(__FILE__) . "/../Epub/Epub.php");

   /**********************************************/
   $bookInfo = array(
      'title'       => '程序员修仙小说',
      'creator'     => 'fengbo',
      'subject'     => 'fengbo',
      'description' => '程序员修仙小说',
      'publisher'   => 'fengbo',
      'contributor' => 'fengbo',
      'date'        => date('Y-m-d'),
      'type'        => '程序员',
      'format'      => 'epub',
      'source'      => '知乎',
      'language'    => 'zh-cn',
      'relation'    => '',
      'coverage'    => '',
      'rights'      => '版权归知乎和原作者所有'
   );

   $bookCover = 'images/bookCover.jpg';

   $mddata = array(
      'md/aa.md'
   );
   $data = array(
   );

   $parser = new Parsedown();
   foreach ($mddata as $mdfile)
   {
      $str ="";
      if(file_exists($mdfile)){
         $str = file_get_contents($mdfile);
      } else {
         echo("markdown file [{$mdfile}] not exists\n");
         exit(1);
      } 
      $title   = strstr($str, "\n", true);
      $con     = substr($str, strlen($title) + 1);
      $content = htmlentities($parser -> text($con));
      $data[$title] = $content;
   }

   $fileName = "programmer-xiuzhen.epub";
   $tempdir = "temp";

   /*************************************************/
   $epub = new EpubPacker($tempdir);

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
      $epub->setData($chapterTitle, $chapterBody, $i);
      $chapterFileName = $epub->saveChapter();
      if( $chapterFileName )
      {
         $epub->addChapter();
         $i++;
      }
   }

   $pack = $epub->saveBook('./', $fileName);

   echo $epub->showError();

   if( $pack )
   {
      echo "epub书籍打包成功！\n";
   }else{
      echo "epub书籍打包失败！\n";
   }

?>

