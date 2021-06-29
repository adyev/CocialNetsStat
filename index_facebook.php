<!DOCTYPE html>
<html>
<head>
  <title>
    My Name 
  </title>
</head>

<body>
  <h1>Get My Name from Facebook</h1>

<?php

require_once __DIR__ . '/vendor/autoload.php';   

$fb = new \Facebook\Facebook([
  'app_id' => '477534216813730',           
  'app_secret' => 'b1fed89a745005456eac9ac6e02e3c91',   
  'graph_api_version' => 'v5.0',
]);


try {
   
// Get your UserNode object, replace {access-token} with your token
  $response = $fb->get('/17841425300638997/media?fields=media_type,comments_count,like_count,media_url,children,permalink,timestamp,caption', 'EAAGyUJlcfKIBAAwDAxoo1LwuZCu4c6x4rLdwhnsShSBkTF1Rp8mg8JjJdxpdjmXidlFykUDEkZAQGZAJavkpBwcYfQ3KlxG8pzBeRdid70I55MJu73TZBiOhnzsnfmdqZBhDCdW2VUmyIVKZAYUccsfoNXhVWsojxROKEByVEM9gZDZD');

} catch(\Facebook\Exceptions\FacebookResponseException $e) {
        // Returns Graph API errors when they occur
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
        // Returns SDK errors when validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}



       //All that is returned in the response
       $array = $response->getDecodedBody();
       
       foreach($array['data'] as $post)
       {
          $srcs = "";
          $comments = $post['comments_count'];
          $likes = $post['like_count'];
          $link = $post['permalink'];
          $date = strtotime($post['timestamp']);
          if(isset($post['caption']))
          {
             $message = $post['caption'];
          }
          if(isset($post['children']))
          {
             foreach($post['children']['data'] as $child)
            {
               $response = $fb->get('/'.$child['id'].'?fields=media_type,media_url', 'EAAGyUJlcfKIBAAwDAxoo1LwuZCu4c6x4rLdwhnsShSBkTF1Rp8mg8JjJdxpdjmXidlFykUDEkZAQGZAJavkpBwcYfQ3KlxG8pzBeRdid70I55MJu73TZBiOhnzsnfmdqZBhDCdW2VUmyIVKZAYUccsfoNXhVWsojxROKEByVEM9gZDZD');
               $child_arr = $response->getDecodedBody();
               $srcs .= $child_arr['media_url'] . ' ';
            }
          }
          else
          {
             $srcs = $post['media_url'];
          }
          
          
       }
       
       

       //Print out my name


?>

</body>
</html>