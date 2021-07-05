<?php
class Cate{
    public $CategoryId;
    public $CategoryName;
    public $CategoryType;
    public $ParentId;
    public $CountPosts;
}

class ParentCate{
    public $ParentId;
    public $ParentName;
}
 
class Post{
    public $PostId;
    public $Title;
    public $ViewNumbere;
    public $Img;
    public $Summary;
    public $Content;
    public $CategoryId;
    public $UserId;
    public $DatePost;
}
?>