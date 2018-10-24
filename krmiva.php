<?php
# XML feed pro ketegorie
# https://www.canis-prosper.cz/export/export_velkoobchod.php?co=catalog&login=prucek@live.com&hash=8fd2168251ec264fcd866d3c503020bb2a375f03

# XML feed s produkty
# https://www.canis-prosper.cz/export/export_velkoobchod.php?co=products&login=prucek@live.com&hash=8fd2168251ec264fcd866d3c503020bb2a375f03
$loadedCats = '<categories>
<category>
<category_id>1196</category_id>
<category_parent_id>0</category_parent_id>
<category_name>Jcat1</category_name>
</category>
<category>
<category_id>1197</category_id>
<category_parent_id>1196</category_parent_id>
<category_name>cat2</category_name>
</category>
<category>
<category_id>705</category_id>
<category_parent_id>1197</category_parent_id>
<category_name>cat3</category_name>
</category>
<category>
<category_id>71</category_id>
<category_parent_id>705</category_parent_id>
<category_name>cat4</category_name>
</category>
</categories>';

$loadProd = '<products>
<product>
<product_id>8848</product_id>
<product_code>SN:090938</product_code>
<part_number/>
<category_id>71</category_id>
<categoryies_id/>
<name>Pes velká tlama 20cm</name>
<short_description/>
<long_description>
<p><span style="font-size: small;">Vinylové pes s širokým úsměvem - pro psy nadšené z pískacích hraček . </span></p> <p><span style="font-size: small;">Rozměr: 20cm</span></p> <p><span style="font-size: small;">Barva: zelená, žlutá, růžová<br /></span></p> <p><span style="font-size: small;">Zasíláme barvu dle aktuální skladové zásoby. Pokud však požadujete výhradně jednu z barev, uveďte to prosím v poli "poznámka".</span></p>
</long_description>
<tech_description/>
<producer>Ostatní</producer>
<action>0</action>
<unit>ks</unit>
<guarantee>0</guarantee>
<price>30.38</price>
<vat>21</vat>
<availability>1</availability>
<availability_all>2</availability_all>
<images>
<image>
https://www.canis-prosper.cz/produkty/original/8848.jpg
</image>
</images>
<recycling_charges>0</recycling_charges>
<ean>8594073532305</ean>
<weight>0.00</weight>
</product>
</products>';

$catUrl = "https://www.canis-prosper.cz/export/export_velkoobchod.php?co=catalog&login=prucek@live.com&hash=8fd2168251ec264fcd866d3c503020bb2a375f03";
$prodUrl = "https://www.canis-prosper.cz/export/export_velkoobchod.php?co=products&login=prucek@live.com&hash=8fd2168251ec264fcd866d3c503020bb2a375f03";

$cats = simplexml_load_file($catUrl);
#$cats = simplexml_load_string($loadedCats);
$prods = simplexml_load_file($prodUrl);
#$prods = simplexml_load_string($loadProd);
$output = [];
$categories = [];
$products = [];
foreach ($cats as $obj) {
		$value = get_object_vars($obj);
		$categories[$value["category_id"]]= $value;

}

foreach ($prods as $obj) {
	$product = get_object_vars($obj);
	$category = getCategoryForId($product["category_id"]);
	if($category) {
		//$product['categories'] = getTree($category);
	}
	$products[$product["product_id"]] = $product;
}

# TODO strukturovat kategorie podle jejich hierachie
$roots = '<roots>';
foreach ($categories as $key => $cat) {
	if($cat["category_parent_id"] == 0){
		    $roots .= getTree($cat); 
	}	
	
}
$roots .= '</roots>';

function getTree($cat, $branch = ''){

	$children = getAllChildren($cat);
	$tree = '';
	print_r(1111111111);
	if(count($children) > 0){
		
		$tree .=  $cat['category_name'] . ' | ' ;
		$branch .= $tree;
		foreach ($children as $key => $child) {			
			$tree .= getTree($child, $branch);
		}

		return $tree;

	} else {
		
		$branch .= $cat['category_name'];
		$products = getProductsForCategoryId($cat['category_id']);		
		foreach ($products as $key => $prod) {
			$GLOBALS['products'][$prod['product_id']]['categories'] = $branch;
		}
		return $branch;
	}
}

function getProductsForCategoryId($id){
	$out = [];
	foreach ($GLOBALS['products'] as $key => $prod) {
		if($id == $prod["category_id"]){
				$out [] = $prod;
		}
	}
	return $out;	
}


function getCategoryForId($id) {
	foreach ($GLOBALS['categories'] as $key => $cat) {
		if($id == $cat["category_id"]){
				return $cat;
		}
	}
	
}

function getAllChildren($cat) {
	$children = [];
	foreach ($GLOBALS['categories'] as $key => $item) {
		if($item["category_parent_id"] == $cat["category_id"]){
				$children [] = $item; 
		}
		
	}
	return $children;
}

$xml_out = "<products>";
foreach ($products as $key => $product) {
		$xml_out .= '<product>
		<product_id>'. $product["product_id"] .'</product_id>
		<product_code>'. $product["product_code"] .'</product_code>
		<part_number/>
		<category_id>'. $product["category_id"] .'</category_id>
		<categories>
		'. $product["categories"] .'
		</categories>		
		<name>'. $product["name"] .'</name>
		<short_description/>
		<long_description>
		'. $product["long_description"] .'
		</long_description>
		<tech_description/>
		<producer>'. $product["producer"] .'</producer>
		<action>'. $product["action"] .'</action>
		<unit>'. $product["unit"] .'</unit>
		<guarantee>'. $product["guarantee"] .'</guarantee>
		<price>'. $product["price"] .'</price>
		<vat>'. $product["vat"] .'</vat>
		<availability>'. $product["availability"] .'</availability>
		<availability_all>'. $product["availability_all"] .'</availability_all>
		<images>
		<image>
		'. $product["image"] .'
		</image>
		</images>
		<recycling_charges>'. $product["recycling_charges"] .'</recycling_charges>
		<ean>'. $product["ean"] .'</ean>
		<weight>'. $product["weight"] .'</weight>
		</product>';
}

$xml_out .= "</products>";

file_put_contents("vystup.xml", $xml_out);
print_r($xml_out);

?>
