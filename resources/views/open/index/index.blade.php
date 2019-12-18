@extends('open.layouts.template1')

@section('js')
@parent
<script>
var searchClick = function(){
    var word = document.getElementById('keyword');
	window.location.href="{{route('search')}}" + "?keyword=" + word.value;
}


var keydown = function(e){
	console.log(e.keyCode );
	if(e.keyCode  == 13){
		searchClick();
    }
}
</script>
@stop

@section('content')
    <div class="banner-smell">
      <img src="images/smell/bg_search.png" alt="">
      <div class="smell-search">
        <div>
          <input type="text" value="" id="keyword" onkeydown="return keydown(event)" class="key" placeholder="请输入要搜索的关键词">
          <button type="button" id="search_btn" onclick="searchClick()" class="sub">
            <span>搜索</span></button>
        </div>
        <p class="hot-search">
          <span>热门搜索：</span>柠檬、栀子花、咖啡、爆米花、大海</p></div>
    </div>
    <div class="content-smell-classify">
      <div class="content-title">
        <h2>气味分类</h2>
        <img src="images/line.png" height="3" width="80" alt="">
        <p class="subhead">SCENT CLASSIFICATION</p></div>
      <div class="content-text">
        <div id="classify-container">
          <div id="classify-list">
            <div>
              <img src="images/smell/Animal.png" data-sort="Animal" /></div>  
            <div>
              <img src="images/smell/classify_tree.jpg" data-sort="trees" /></div>
            <div>
              <img src="images/smell/classify_fruit.jpg" data-sort="fruits" /></div>
            <div>
              <img src="images/smell/classify_vegetable.jpg" data-sort="vegetables" /></div>
            <div>
              <img src="images/smell/classify_flowers.jpg" data-sort="flowers" /></div>
              <div>
              <img src="images/smell/Animal.png" data-sort="Animal" /></div>  
            <div>
              <img src="images/smell/classify_tree.jpg" data-sort="trees" /></div>
            <div>
              <img src="images/smell/classify_fruit.jpg" data-sort="fruits" /></div>
            <div>
              <img src="images/smell/classify_vegetable.jpg" data-sort="vegetables" /></div>
            <div>
              <img src="images/smell/classify_flowers.jpg" data-sort="flowers" /></div>
          </div>
        </div>
        <a href="javascript:;" id="prev-btn" class="arrow"></a>
        <a href="javascript:;" id="next-btn" class="arrow"></a>
        <div class="show-smell-card">
          <ul class="smell-list"></ul>
          <!-- <div class="more"></div> --></div>
      </div>
      <div class="backtop">
        <div class="btnTo"></div>
      </div>
    </div>
@stop