<?
$icons = array("⏹", "🛪", "⛾", "🕮", "👍", "💩", "🐦", "🐱", "🗺", "📱", "📧", "🗨", "🌲", "🍴", "💳", "🖋", "🎂", "👣", "🦄");

function slugify($string){
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
}

if(isset($_POST["submit3"])){

    $data["chart_name"] = $_POST["chart_name"];

    foreach($_POST["things"] as $slug => $thing_name){
        $j = array_search($slug, array_keys($_POST["things"]));
        $data["x"][$j]["name"] = $thing_name;
        if(isset($_POST["block_label"][$slug])){
            foreach($_POST["block_label"][$slug] as $i => $val){
                $data["x"][$j]["y"][$i]["name"] = $_POST["block_label"][$slug][$i];
                $data["x"][$j]["y"][$i]["colour"] = $_POST["block_colour"][$slug][$i];
                $data["x"][$j]["y"][$i]["icon"] = $_POST["block_icon"][$slug][$i];
            }
        }
    }

    $html = "
<article>
    <h2>{$data["chart_name"]}</h2>
    <div class=\"container\">";

    foreach($data["x"] as $x){
        $html .= "
        <div>
            <h3>{$x["name"]}</h3>";
        if(isset($x["y"])){
            foreach($x["y"] as $y){
                $html .= "
            <p style=\"color: {$y["colour"]}\" title=\"{$y["name"]}\">{$y["icon"]}</p>";
            }
        }
        $html .= "
        </div>";
    }
    $html .= "
    </div>
</article>";

    $css = "<style type=\"text/css\">
    .container {
        display: flex;
        width: 100%;
        border-bottom: 1px solid rgba(0, 0, 0, 0.44);
        border-left: 1px solid rgba(0, 0, 0, 0.44);
    }
    .container div {
        flex: 1;
        padding: 0.2em;
        text-align: center;
    }
    .container div p {
        padding: 0; margin: 0;
        margin-top: -0.8em;
        cursor: pointer;
        font-size: 2em;
    }
</style>";

}
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="normalize.min.css" />
        <link rel="stylesheet" type="text/css" href="base.css" />
    </head>
    <body>
        <main>
            <h1>argus array</h1>
<?if(empty($_POST)):?>
            <form id="part1" method="post">
                <p>
                    <label>Name of chart</label>
                    <input type="text" name="name" value="A Chart" />
                </p>
                <p>
                    <label>Number of things on x axis</label>
                    <input type="number" name="number" value="2" />

                    <label>Icon for blocks on y axis</label>
                    <select name="icon">
                        <?foreach($icons as $icon):?>
                            <option value="<?=$icon?>"><?=$icon?></option>
                        <?endforeach?>
                    </select>
                </p>
                <input type="submit" name="submit1" value="next" /> <a href="">reset</a>
            </form>
<?endif?>
<?if(isset($_POST["submit1"])):?>
            <form id="part2" method="post">
                <input type="text" name="chart_name" readonly value="<?=$_POST['name']?>" />
                <?for($i=1;$i<=$_POST["number"];$i++):?>
                <p>
                    <label>Name of thing <?=$i?></label>
                    <input type="text" name="thing_name[]" value="thing <?=$i?>" />
                </p>
                <p>
                    <label>Icon</label>
                    <select name="thing_icon[]">
                        <?foreach($icons as $icon):?>
                            <option value="<?=$icon?>"<?=($icon==$_POST['icon']) ? " selected": ""?>><?=$icon?></option>
                        <?endforeach?>
                    </select>

                    <label>Colour</label>
                    <input type="color" name="thing_colour[]" />

                    <label>Number of blocks on y axis</label>
                    <input type="number" name="thing_blocks[]" value="1" />
                </p>
                <?endfor?>
                <input type="submit" name="submit2" value="next" /> <a href="">reset</a>
            </form>
<?endif?>
<?if(isset($_POST["submit2"])):?>
            <form id="part3" method="post">
                <input type="text" name="chart_name" readonly value="<?=$_POST['chart_name']?>" />
            <?for($i=0;$i<count($_POST["thing_name"]);$i++):?>
                <? $thing_slug = slugify($_POST["thing_name"][$i]); ?>
                <h3><?=$_POST["thing_name"][$i]?></h3>
                <input type="hidden" value="<?=$_POST['thing_name'][$i]?>" name="things[<?=$thing_slug?>]" />
                <?for($j=1;$j<=$_POST["thing_blocks"][$i];$j++):?>
                <p>
                    <label>Block <?=$j?> label</label>
                    <input type="text" name="block_label[<?=$thing_slug?>][]" />

                    <label>icon</label>
                    <select name="block_icon[<?=$thing_slug?>][]">
                        <?foreach($icons as $icon):?>
                            <option value="<?=$icon?>"<?=($icon==$_POST['thing_icon'][$i]) ? " selected": ""?>><?=$icon?></option>
                        <?endforeach?>
                    </select>

                    <label>colour</label>
                    <input type="color" name="block_colour[<?=$thing_slug?>][]" value="<?=$_POST['thing_colour'][$i]?>" />
                </p>
                <?endfor?>
            <?endfor?>
            <input type="submit" name="submit3" value="generate chart" /> <a href="">reset</a>
            </form>
<?endif?>
<?if(isset($_POST["submit3"])):?>
<?=$html?>
<pre>
<?=htmlentities($css)?>
<?=htmlentities($html)?>
</pre>
<p><a href="">make another</a></p>
<?endif?>
            <p style="text-align: right"><a href="examples.html">examples</a> | <a href="https://github.com/rhiaro/argus-array">source code</a></p>
        </main>
    </body>
</html>