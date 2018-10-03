````<?php
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
<category_id>77</category_id>
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

#$cats = simplexml_load_file($catUrl);
$cats = simplexml_load_string($loadedCats);
#$prods = simplexml_load_file($prodUrl);
$prods = simplexml_load_string($loadProd);
$output = [];
$categories = [];
$products = [];
foreach ($cats as $obj) {
		$value = get_object_vars($obj);
		$categories[$value["category_id"]]= $value;

}

foreach ($prods as $obj) {
	$value = get_object_vars($obj);
	$products[$value["product_id"]] = $value;
}

$toRemove = [];
# TODO strukturovat kategorie podle jejich hierachie
$c = [];
foreach ($categories as $key => $cat) {
	if($cat["category_parent_id"] == 0){
		//$c[$key] = $cat;
	}
	
}
foreach ($categories as $key => $cat){
		$id = $cat["category_id"];
		foreach ($categories as $ss => $category){
			$parentId = $category["category_parent_id"];
			$childId = $category["category_id"];
			if($parentId === $id){
				$c[$parentId]["categories"][] =  $categories[$childId];

				$toRemove[]= $childId;
			}
		}
		
}

foreach ($toRemove as $key) {
	unset($categories[$key]);
}

foreach ($categories as $key => $value) {
	//print_r($key);
	foreach ($value["categories"] as $key => $child) {
		//print_r($child);
	}
	
}
print_r($c);

foreach ($products as $prod) {
	#$value = get_object_vars($obj);
	#$products[$value["product_id"]] = $value;
	$id =  $prod["product_id"];
	$categoryId = $prod["category_id"];
	$category = $categories[$categoryId];
	#print($prod["category_id"]);

	$output[$id]["category"][$categoryId] = $category;
}


#print_r($output);

?>````
