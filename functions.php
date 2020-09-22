<?php

/**
 * @desc Kullanıcı adına göre instagram gönderi listeleme
 * @param $username
 */
function ListPostsByUsername($username)
{
  $url = "https://www.instagram.com/{$username}/?__a=1";
  $post = callAPI('GET', $url, null);
  $json = json_decode($post);
  $medias = array();
  $mediasArr = $json->graphql->user->edge_owner_to_timeline_media->edges;
  foreach ($mediasArr as $key => $value) {
    $image = $value->node->display_url;
    $text = $value->node->edge_media_to_caption->edges[0]->node->text;
    $medias[$key][] = $image;
    $medias[$key][] = $text;
  }
  return json_encode($medias);
}

/**
 * @desc API çağırma methodu
 * @param $method
 * @param $url
 * @param $data
 * @return bool|string
 */
function callAPI($method, $url, $data)
{
  $curl = curl_init();

  switch ($method) {
    case "POST":
      curl_setopt($curl, CURLOPT_POST, 1);
      if ($data)
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      break;
    case "PUT":
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
      if ($data)
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      break;
    default:
      if ($data)
        $url = sprintf("%s?%s", $url, http_build_query($data));
  }

  // OPTIONS:
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
  ));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

  // EXECUTE:
  $result = curl_exec($curl);
  if (!$result) {
    die("Connection Failure");
  }
  curl_close($curl);
  return $result;
}