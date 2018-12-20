<?php

    // Setup

    $elements = array();
    $trackNumber = $_GET['tracknumber'];

    // Determine what playlist was selected

    if( $_GET['genre'] == 'Play Hip Hop & Rap' ){
        $genre = 'Hip Hop & Rap';
        $image = 'http://vincentgreenfield.com/bbb/images/hhrbg.png';
        $playlistID = '344850878';
    }

    if( $_GET['genre'] == 'Play Electronic' ){
        $genre = 'Electronic';
        $image = 'http://vincentgreenfield.com/bbb/images/elecbg.png';
        $playlistID = '344848600';
    }

    if( $_GET['genre'] == 'Play Indie' ){
        $genre = 'Indie';
        $image = 'http://vincentgreenfield.com/bbb/images/indiebg.png';
        $playlistID = '344925212';
    }

    if( $_GET['genre'] == 'Play Chill' ){
        $genre = 'Chill';
        $image = 'http://vincentgreenfield.com/bbb/images/chillbg.png';
        $playlistID = '344925043';
    }

    if( $_GET['genre'] == 'Play Remixes' ){
        $genre = 'Remixes';
        $image = 'http://vincentgreenfield.com/bbb/images/chillbg.png';
        $playlistID = '354330127';
    }

    // Get the 3 most recent songs from a playlist

    $json = file_get_contents('http://api.soundcloud.com/playlists/'. $playlistID . '?client_id=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
    $playlistData = json_decode( $json, true );

    // Get Song Data from SoundCloud
    $playlistData = array_reverse( $playlistData['tracks'] );
    $songData = $playlistData[$trackNumber-1];

    // Display song info
    if( $songData != null ){
        if( strpos(  $songData['title'], '-') !== false){
            $songLabel =  $songData['title'];
        } else {
            $songLabel =  $songData['title'] . ' - ' . $songData['user']['username'];
        }
        $songDetails = array(
            'title'     => $songLabel,
            'image_url' => str_replace( 'large', 't500x500', $songData['artwork_url'] ),
            'subtitle'  => '❤️ '.number_format($songData['favoritings_count']).' people like this',
            'buttons'   => array(
                            array(
                                'type' => 'show_block',
                                'block_name' => 'Add to Library',
                                'title' => '➕ Add to Library'
                            )
                        )
        );
        array_push( $elements, $songDetails );
        echo '{ "set_attributes": { "songid": "'.$songData['id'].'" }, "messages": [ { "attachment": { "type": "audio", "payload": { "url": "'.$songData['stream_url'].'?client_id=204b391fc795204d0bdf863b9069dd99"}}}, { "attachment": { "type": "template", "payload": { "template_type": "generic", "elements": '. json_encode($elements,JSON_UNESCAPED_SLASHES) .'}},"quick_replies": [ { "title": "Next Song", "block_names":["Get Track"] } ] } ] }';

    }

    // If no more songs exist, display closing message
    else {
        echo '{"messages": [ {"text": "Thats the end of the '.$genre.' playlist. Check back tomorrow for more tracks!","quick_replies": [ { "title": "Back to Genres", "block_names":["Genre Selector"] } ]} ] }';
    }


?>
