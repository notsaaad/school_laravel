<?php

namespace App\Console\Commands;

use App\Console\Commands\eslam\validate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class controller extends Command
{

    use validate;

    protected $signature = 'eslam:controller {--img}';
    protected $description = 'create crud controller';


    public function handle()
    {


        $name = $this->askValid('Enter : Model name ?', "Model name", ['required', 'min:3']);
        $path = $this->askValid('Enter : Controller path ?', "Controller path", ['required', 'min:3']);

        if (!$this->confirm("Model { $name } path { $path }  continue?", true)) {
            $this->info("close");
            return;
        }

        $directory = app_path("Http/Controllers/{$path}");

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = "{$directory}/{$name}Controller.php";

        if (File::exists($filename)) {
            $this->error('Controller already exists!');
            return;
        }

        File::put($filename, $this->controllerTemplate($path, $name));
        $this->info("Controller created: {$name}Controller.php");
    }

    protected function controllerTemplate($path, $name)
    {

        $templatePath = app_path("Console/Commands/eslam/controller/basic.php");
        $template = File::get($templatePath);
        $template .= $this->storeFunction($name);
        $template .= $this->updateFunction($name);
        $template = str_replace('_ControllerPath', str_replace('/', '\\', $path), $template);
        $template = str_replace('_ControllerName', "{$name}Controller", $template);
        $template = str_replace('_ModelName', $name, $template);
        return $template;
    }

    protected function storeFunction($name)
    {

        $fillable = (new ("\App\Models\\" . $name))->getFillable();


        if ($this->option('img')) {
            $validationRules = "";
            foreach ($fillable as $field) {
                if ($field == "img") {
                    $validationRules .= "'$field' => 'required|mimes:jpeg,png,jpg,gif,webp|size:2048',\n";
                } else {
                    $validationRules .= "'$field' => 'required|string',\n";
                }
            }

            return <<<store_function
                public function store(Request \$request)
                {
                    \$data = \$request->validate([
                        $validationRules
                    ],[
                        "img.size"=> "لا يمكن اي يزيد حجم الصورة عن 2 ميجا"
                    ]);

                    \$data["img"] = Storage::put("public/_ModelName" , \$data['img']) ;
                    \$data = \$request->validate(\$data);
                    category::create(\$data);
                    return Redirect::back()->with("success", "تم الاضافة بنجاح");
                }
                store_function;
        }

        $validationRules = "";
        foreach ($fillable as $field) {
            $validationRules .= "'$field' => 'required|string',\n";
        }


        return <<<store_function
        public function store(Request \$request)
        {
            \$data = \$request->validate([
                $validationRules
            ]);
            _ModelName::create(\$data);
            return Redirect::back()->with("success", "تم الاضافة بنجاح");
        }
        store_function;
    }

    protected function updateFunction($name)
    {

        $fillable = (new ("\App\Models\\" . $name))->getFillable();


        if ($this->option('img')) {
            $validationRules = "";
            foreach ($fillable as $field) {
                if ($field == "img") {
                    $validationRules .= "'$field' => 'nullable|mimes:jpeg,png,jpg,gif,webp|size:2048',\n";
                } else {
                    $validationRules .= "'$field' => 'required|string',\n";
                }
            }

            return <<<update
                public function update(Request \$request , _ModelName \$_ModelName)
                {
                    \$data = \$request->validate([
                        $validationRules
                    ],[
                        "img.size"=> "لا يمكن اي يزيد حجم الصورة عن 2 ميجا"
                    ]);

                    \$data = \$request->validate(\$data);

                    if (isset(\$data['img']) ) {
                        \$data["img"] = Storage::put("public/_ModelName" ,\$data['img']);
                    }

                    \$_ModelName->update(\$data);
                    return Redirect::back()->with("success", "تم التعديل بنجاح");
                }
                update;
        }

        $validationRules = "";
        foreach ($fillable as $field) {
            $validationRules .= "'$field' => 'required',\n";
        }
        return <<<update
        public function update(Request \$request , _ModelName \$_ModelName)
        {
            \$data = \$request->validate([
                $validationRules
            ]);
            \$_ModelName->update(\$data);
            return Redirect::back()->with("success", "تم التعديل بنجاح");
        }
        update;
    }
}
