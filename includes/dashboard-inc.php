
<?php
require_once 'dbconfig.php';

?>

<style type="text/css" media="screen">
    #business_post {
        margin: 1em;
    }

    #post_options {
        height: 25px;
    }

        #post_options img {
            height: 100%;
        }

    .card {
        border: none;
    }

    .card-header {
    }

    #fileInputIcon {
        cursor: pointer;
    }

        #fileInputIcon:hover {
            border-color: teal;
        }

    .image_holder {
        width: 25px;
        height: 25px;
        margin-right: 8px;
        padding: 0px;
        cursor: pointer;
    }

        .image_holder img:hover {
            -webkit-filter: contrast(200%) brightness(150%); /* Safari */
            filter: contrast(200%) brightness(150%);
            -webkit-transform: rotateY(180deg); /* Safari */
            transform: rotateY(180deg);
            transition-duration: 1s;
            filter: hue-rotate(80deg);
        }

    img:hover {
        -webkit-filter: brightness(120%); /* Safari */
        filter: brightness(120%);
        filter: hue-rotate(30deg);
    }

    input[type='file'] {
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        height: 100%;
        width: 100%;
        font-size: 0;
    }

    .load_posts .card {
        box-shadow: 1px 1px 3px 1px rgba(0,0,0,0.1);
        margin-top: 4px;
    }

    .share_toggler, .instagram_share, .twitter_share, .facebook_share {
        box-shadow: 1px 4px 10px 1px rgba(0,0,0,0.3);
    }

    .social_hide {
        display: none;
    }

    .postview img {
        width: 100%;
    }

    .postview {
        clear: both;
    }

    .post_entry_box {
        box-shadow: 1px 1px 20px 1px rgba(0,0,0,0.1);
        padding: 0 0 9px 0;
    }

    .load_posts {
        padding: 10px 80px;
    }

    @media(max-width: 720px) {
        .load_posts {
            padding: 10px 10px;
        }
    }
    .btn , .allFormData{
        z-index: 0;
    }
    
</style>

<div class="animated fadeIn row col-lg-12 col-md-12" style="height: 100%;">
    <div class="col-lg-9 col-md-9 col-sm-12" style="height: 100%; overflow-y: scroll;">
        <div class="row post_entry_box">
            <div class="card col-lg-10 col-md-10 left_section">
                <div class="card-header">
                    <h5>Let other businesses know what you are up to? Share a marketing trick? Watch your competitors moves!</h5>
                </div>
                <form class="allFormData" method="post" enctype="multipart/form-data">
                    <div class="input-group business_post_cont">
                        <textarea rows="3" spellcheck="false" name="business_post" id="business_post" class="form-control" autofocus="autofocus"></textarea>
                    </div>
                </form>
                <div class="text-center">
                    <div id="post_result" class="float-left" style="display: none;">
                        <!-- POST RESULT WILL BE SHOWN HERE -->
                    </div>
                    <div id="post_options" class="float-right row">
                        <form class="allFormData" method="post" enctype="multipart/form-data">
                            <span class="input-group image_holder text-right">
                                <img src="../images/camera.jpg" alt="" id="image_selector" title="Upload an image" />
                                <input type="file" id="post_image" name="post_image" />
                            </span>
                        </form>&nbsp;
                        <span class="btn btn-sm btn-outline-info text-uppercase" style="margin-right: 2px" id="post_post">
                            post
                            <span class="mdi mdi-youtube-play"></span>
                        </span>
                        <span class="btn btn-sm btn-outline-warning text-uppercase" id="discard">
                            discard
                            <span class="mdi mdi-incognito"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 hidden-md-down" style="height: 100%">
                <h6>You can also post an image. You might be the next big thing to a certain business</h6>
                <div class="image_display" style="height: 100px; width: 110px; display: none;">
                    <img src="#" id="image_display" alt="" style="height: 100%; width: 100%" />
                </div>
            </div>
        </div>
        <div class="load_posts animated fadeIn">

            <!-- POSTS, NEW POSTS AND FEEDS WILL BE LOADED HERE -->
            <div class="text-center post_preloader" style="padding: 100px 0">
                <img src="../images/preloader/25.gif" alt="" style="height: 30px; width: 30px" />
            </div>

        </div>

        

        <!-- LOAD MORE BUTTON TO BE PLACED HERE -->
        
        <div class="preloader_content" style="display: none;">
            <img src="../images/preloader/25.gif" alt="" style="height: 30px; width: 30px" />
        </div>
    </div>

    <div class="col-lg-3 col-md-3 hidden-md-down rss_feed" style="height: 100%; overflow-y: scroll; z-index: -1;">
        
        <!-- LOAD RSS FEED HERE  -->

    </div>
    
</div>

<script type="text/javascript">
    $( function () {

        
        // function to fecth rss feeds
        ( function refreshRSS () {
            $.post( '../includes/rss_section.php', {
                fetch_rss: true
            }, function ( data ) {
                // succesfull operation
                $( '.rss_feed' ).html( data );

                $( '.rss_feed' ).animate( {
                    scrollTop: $( '.rss_feed' ).get( 0 ).scrollHeight
                }, 3000 );

                setTimeout( refreshRSS, 4000 );
            } );
        } )();
        
        $( '#discard' ).click( function ( event ) {
            /* Act on the event */
            $( '#business_post' ).val( "" ).focus();
            $( '#business_post' ).attr( 'placeholder', 'Type what you are up to.......' );
        } );

        $( '#image_selector' ).click( function ( event ) {
            /* Act on the event */
            $( '#post_image' ).click();
        } );


        // function to check news feed for new updates

        ( function updateFeed() {
            $.post( '../includes/pull-feeds-tome.php?fetchNew=true', function ( data ) {

                $( '.load_posts' ).html( data );
                setTimeout( updateFeed, 25000 );
            } );
        } )();

        $( '#post_post' ).click( function ( event ) {
            /* Act on the event */
            var $postToPost = $( '#business_post' ).val().trim();
            $( '#post_result' ).html( $( '.preloader_content' ).html() );
            if ( $postToPost == '' ) {
                $( '.business_post_cont' ).addClass( 'has-danger' );
                $( '#business_post' ).addClass( 'form-control-danger' );
                $( '#business_post' ).focus();
                $( '#business_post' ).attr( 'placeholder', 'Your post is empty' );
            } else {
                $( '.business_post_cont' ).removeClass( 'has-danger' );
                $.ajax( {
                    url: '../includes/pull-feeds-tome.php?postContent=' + $postToPost,
                    method: 'POST',
                    data: new FormData( $( '.allFormData' )[1] ),
                    async: false,
                    success: function ( resultText ) {
                        // execute after success

                        // reset input of type file to blank
                        $( '.allFormData' )[1].reset();

                        // reset text area to blank
                        $( '#business_post' ).val( '' );

                        $( '.image_display' ).slideUp();
                        $( '#business_post' ).focus();
                        $( '#business_post' ).attr( 'placeholder', 'Post a new post, you can also add images........' );
                        $( '#post_result' ).fadeIn( 'slow', function () {
                            $( this ).slideUp( 5000 );
                            $( '#post_result' ).html( resultText );
                        } );
                        $.post( '../includes/pull-feeds-tome.php?fetchNew=true', function ( data ) {
                            /*optional stuff to do after success */
                            $( '.load_posts' ).html( data );
                        } );
                    },
                    error: function () {
                        alert( "Your post was not submitted." );
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                } )
            }
        } );

        // function for image preview before upload
        function readImageURL( input ) {
            if ( input.files && input.files[0] ) {
                var reader = new FileReader();
                reader.onload = function ( e ) {
                    /* Act on the event */
                    $( '#image_display' ).attr( 'src', e.target.result );
                    $( '.image_display' ).show();
                }
                reader.readAsDataURL( input.files[0] );
            }
        }
        $( '#post_image' ).change( function ( input ) {
            /* Act on the event */
            // $('#select_image').hide();
            $( '.image_display' ).show();
            readImageURL( this );
        } );
    } )
</script>