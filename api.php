<?php

function get_wells() {
    $query = "SELECT d.*,i.description,i.img FROM h2openmap_field_data d LEFT JOIN h2openmap_field_data_information i ON d.'ref:h2openmap' = i.'ref:h2openmap'";
    $db = new PDO('sqlite:h2openmap.db');
    $res = $db->query($query);
    $geoj=["type"=>"FeatureCollection","features"=>[]];
    $ft=[];
    while($r = $res->fetch( PDO::FETCH_ASSOC )){ 
        $feat=["type"=>"Feature","properties"=>[],"geometry"=>["type"=>"Point", "coordinates"=>[]]];
        $feat["geometry"]["coordinates"]=[$r['lon'],$r['lat']];
        unset($r['lat']);
        unset($r['lon']);
        $feat["properties"]=$r;
        $ft[]=$feat;
    }
    $geoj['features']=$ft;
    $db = NULL;
    return $geoj;
}

if(isset($_GET['wells'])) {
    header('Content-type: application/json');
    echo json_encode(get_wells(), JSON_NUMERIC_CHECK);
}
