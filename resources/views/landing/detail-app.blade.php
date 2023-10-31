@extends('landing.layout.header')

@section('title')
    Detail App
@endsection

@section('content')
    <!-- ***** Featured Games Start ***** -->
    <div class="row">
        <div class="col-lg-8">
          <div class="featured-games header-text">
            <div class="heading-section">
              <h4><em>Category</em> Game Android</h4>
            </div>
            <div class="owl-features owl-carousel">
              <div class="item">
                <div class="thumb">
                  <img src="{{asset('cyborg/assets/images/featured-01.jpg')}}" alt="">
                  <div class="hover-effect">
                    <h6>2.4K Streaming</h6>
                  </div>
                </div>
                
              </div>
              <div class="item">
                <div class="thumb">
                  <img src="{{asset('cyborg/assets/images/featured-02.jpg')}}" alt="">
                  <div class="hover-effect">
                    <h6>2.4K Streaming</h6>
                  </div>
                </div>
                
              </div>
              <div class="item">
                <div class="thumb">
                  <img src="{{asset('cyborg/assets/images/featured-03.jpg')}}" alt="">
                  <div class="hover-effect">
                    <h6>2.4K Streaming</h6>
                  </div>
                </div>
                
              </div>
              <div class="item">
                <div class="thumb">
                  <img src="{{asset('cyborg/assets/images/featured-01.jpg')}}" alt="">
                  <div class="hover-effect">
                    <h6>2.4K Streaming</h6>
                  </div>
                </div>
                
              </div>
              <div class="item">
                <div class="thumb">
                  <img src="{{asset('cyborg/assets/images/featured-02.jpg')}}" alt="">
                  <div class="hover-effect">
                    <h6>2.4K Streaming</h6>
                  </div>
                </div>
                
              </div>
              <div class="item">
                <div class="thumb">
                  <img src="{{asset('cyborg/assets/images/featured-03.jpg')}}" alt="">
                  <div class="hover-effect">
                    <h6>2.4K Streaming</h6>
                  </div>
                </div>
                
              </div>
            </div>
            <div class="game-detail">
                <div class="title-game mt-3">
                    <h4> Game Android</h4>
                </div>
                <div class="description-game mt-4">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce consectetur arcu leo. Vivamus vitae urna metus. Nunc pellentesque nulla nisi, ac pharetra sem vulputate vel. Proin volutpat ornare posuere. Praesent a mattis erat. Pellentesque interdum posuere ipsum, ac egestas risus. Donec aliquet, odio accumsan ultrices blandit, augue ex dapibus nibh, sodales sodales risus tellus eget mauris. Nunc tempus non metus ac condimentum. Aenean dolor neque, fringilla in elit eu, ultricies tempus ante. Proin enim felis, placerat eu erat non, rutrum venenatis neque.

                        </p>
                </div>

                <div class="main-button mt-4">
                    <a href="profile.html">Download</a>
                  </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="top-downloaded">
            <div class="heading-section">
              <h4><em>Top</em> Downloaded</h4>
            </div>
            <ul>
              <li>
                <img src="{{asset('cyborg/assets/images/game-01.jpg')}}" alt="" class="templatemo-item">
                <h4>Fortnite</h4>
                <h6>Sandbox</h6>
                <span><i class="fa fa-star" style="color: yellow;"></i> 4.9</span>
                <span><i class="fa fa-download" style="color: #ec6090;"></i> 2.2M</span>
                <div class="download">
                  <a href="#"><i class="fa fa-download"></i></a>
                </div>
              </li>
              <li>
                <img src="{{asset('cyborg/assets/images/game-02.jpg')}}" alt="" class="templatemo-item">
                <h4>CS-GO</h4>
                <h6>Legendary</h6>
                <span><i class="fa fa-star" style="color: yellow;"></i> 4.9</span>
                <span><i class="fa fa-download" style="color: #ec6090;"></i> 2.2M</span>
                <div class="download">
                  <a href="#"><i class="fa fa-download"></i></a>
                </div>
              </li>
              <li>
                <img src="{{asset('cyborg/assets/images/game-03.jpg')}}" alt="" class="templatemo-item">
                <h4>PugG</h4>
                <h6>Battle Royale</h6>
                <span><i class="fa fa-star" style="color: yellow;"></i> 4.9</span>
                <span><i class="fa fa-download" style="color: #ec6090;"></i> 2.2M</span>
                <div class="download">
                  <a href="#"><i class="fa fa-download"></i></a>
                </div>
              </li>
            </ul>
            <div class="text-button">
              <a href="profile.html">View All Games</a>
            </div>
          </div>
        </div>
      </div>
      <!-- ***** Featured Games End ***** -->
      
      <!-- ***** Featured Games Start ***** -->
      <div class="container">
        <div class="row bootstrap snippets bootdeys">
            <div class="col-lg-12">
                <div class="comment-wrapper">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Comment panel
                        </div>
                        <div class="panel-body">
                            <textarea class="form-control bg-dark text-white" placeholder="write a comment..." rows="3"></textarea>
                            <br>
                            <button type="button" class="btn btn-secondary pull-right">Post</button>
                            <div class="clearfix"></div>
                            <hr>

                        </div>
                        <div class="comment-area">
                            <ul class="media-list">
                                <li class="media">
                                    <a href="#" class="pull-left">
                                        <img src="https://bootdey.com/img/Content/user_1.jpg" alt="" class="img-circle">
                                    </a>
                                    <div class="media-body">
                                        <span class="text-muted pull-right">
                                            <small class="text-muted">30 min ago</small>
                                        </span>
                                        <strong class="text-success">@MartinoMont</strong>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Lorem ipsum dolor sit amet, <a href="#">#consecteturadipiscing </a>.
                                        </p>
                                    </div>
                                </li>
                                <li class="media">
                                    <a href="#" class="pull-left">
                                        <img src="https://bootdey.com/img/Content/user_2.jpg" alt="" class="img-circle">
                                    </a>
                                    <div class="media-body">
                                        <span class="text-muted pull-right">
                                            <small class="text-muted">30 min ago</small>
                                        </span>
                                        <strong class="text-success">@LaurenceCorreil</strong>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                            Lorem ipsum dolor <a href="#">#ipsumdolor </a>adipiscing elit.
                                        </p>
                                    </div>
                                </li>
                                <li class="media">
                                    <a href="#" class="pull-left">
                                        <img src="https://bootdey.com/img/Content/user_3.jpg" alt="" class="img-circle">
                                    </a>
                                    <div class="media-body">
                                        <span class="text-muted pull-right">
                                            <small class="text-muted">30 min ago</small>
                                        </span>
                                        <strong class="text-success">@JohnNida</strong>
                                        <p>
                                            Lorem ipsum dolor <a href="#">#sitamet</a> sit amet, consectetur adipiscing elit.
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        
            </div>
        </div>
        </div>
      <!-- ***** Featured Games End ***** -->



      <!-- ***** Live Stream Start ***** -->
      <div class="live-stream">
        <div class="col-lg-12">
          <div class="heading-section">
            <h4><em>Most Popular</em> App</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-sm-6">
            <div class="item">
              <div class="thumb">
                <img src="{{asset('cyborg/assets/images/stream-01.jpg')}}" alt="">
                <div class="hover-effect">
                  <div class="content">
                    <div class="live">
                      <a href="#">Live</a>
                    </div>
                    <ul>
                      <li><a href="#"><i class="fa fa-eye"></i> 1.2K</a></li>
                      <li><a href="#"><i class="fa fa-gamepad"></i> CS-GO</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="down-content">
                <div class="avatar">
                  <img src="{{asset('cyborg/assets/images/avatar-01.jpg')}}" alt="" style="max-width: 46px; border-radius: 50%; float: left;">
                </div>
                <span><i class="fa fa-check"></i> KenganC</span>
                <h4>Just Talking With Fans</h4>
              </div> 
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="item">
              <div class="thumb">
                <img src="{{asset('cyborg/assets/images/stream-02.jpg')}}" alt="">
                <div class="hover-effect">
                  <div class="content">
                    <div class="live">
                      <a href="#">Live</a>
                    </div>
                    <ul>
                      <li><a href="#"><i class="fa fa-eye"></i> 1.2K</a></li>
                      <li><a href="#"><i class="fa fa-gamepad"></i> CS-GO</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="down-content">
                <div class="avatar">
                  <img src="{{asset('cyborg/assets/images/avatar-02.jpg')}}" alt="" style="max-width: 46px; border-radius: 50%; float: left;">
                </div>
                <span><i class="fa fa-check"></i> LunaMa</span>
                <h4>CS-GO 36 Hours Live Stream</h4>
              </div> 
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="item">
              <div class="thumb">
                <img src="{{asset('cyborg/assets/images/stream-03.jpg')}}" alt="">
                <div class="hover-effect">
                  <div class="content">
                    <div class="live">
                      <a href="#">Live</a>
                    </div>
                    <ul>
                      <li><a href="#"><i class="fa fa-eye"></i> 1.2K</a></li>
                      <li><a href="#"><i class="fa fa-gamepad"></i> CS-GO</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="down-content">
                <div class="avatar">
                  <img src="{{asset('cyborg/assets/images/avatar-03.jpg')}}" alt="" style="max-width: 46px; border-radius: 50%; float: left;">
                </div>
                <span><i class="fa fa-check"></i> Areluwa</span>
                <h4>Maybe Nathej Allnight Chillin'</h4>
              </div> 
            </div>
          </div>
          <div class="col-lg-3 col-sm-6">
            <div class="item">
              <div class="thumb">
                <img src="{{asset('cyborg/assets/images/stream-04.jpg')}}" alt="">
                <div class="hover-effect">
                  <div class="content">
                    <div class="live">
                      <a href="#">Live</a>
                    </div>
                    <ul>
                      <li><a href="#"><i class="fa fa-eye"></i> 1.2K</a></li>
                      <li><a href="#"><i class="fa fa-gamepad"></i> CS-GO</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="down-content">
                <div class="avatar">
                  <img src="{{asset('cyborg/assets/images/avatar-04.jpg')}}" alt="" style="max-width: 46px; border-radius: 50%; float: left;">
                </div>
                <span><i class="fa fa-check"></i> GangTm</span>
                <h4>Live Streaming Till Morning</h4>
              </div> 
            </div>
          </div>
          <div class="col-lg-12">
            <div class="main-button">
              <a href="streams.html">Discover All Streams</a>
            </div>
          </div>
        </div>
      </div>
      <!-- ***** Live Stream End ***** -->
@endsection