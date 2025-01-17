<?php

namespace App\Console\Commands\eslam;

use Illuminate\Console\Command;

class auto_fillable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eslam:fillable {path} {model}';
    protected $description = 'auto model fillable';


    public function handle()
    {
        $migrationFilePath = "Database\Migrations\\" . $this->argument('path') . ".php";
        $migrationContent = file_get_contents($migrationFilePath);
        preg_match_all('/\$table->(\w+)\((?:\'\w+\'(?:, \d+)?(?:, \d+)?)?,? ?\'(\w+)\'/', $migrationContent, $matches);
        $columns = $matches[2];


        $modelFilePath = "App\Models\\" . $this->argument('model') . ".php";

        // Read the existing model file content
        $existingContent = file_get_contents($modelFilePath);

        // Check if $fillable property already exists
        if (preg_match('/protected \$fillable\s*=\s*\[([^\]]+)\]/', $existingContent, $matches)) {
            // $fillable property exists
            $existingFillable = $matches[0];
            // Extract existing columns
            $existingColumns = preg_split('/,\s*/', $matches[1], -1, PREG_SPLIT_NO_EMPTY);


            // Merge existing columns with new columns, removing duplicates and trimming extra quotes
            $mergedColumns = array_map(function ($col) {
                return trim($col, "'");
            }, array_unique(array_merge($existingColumns, $columns)));

            // Construct updated $fillable content
            $newFillable = "protected \$fillable = ['" . implode("', '", $mergedColumns) . "']";

            $mergedColumns = array_unique($mergedColumns);

            // Construct updated $fillable content
            $newFillable = "protected \$fillable = ['" . implode("', '", $mergedColumns) . "']";

            // Replace the existing $fillable content with updated content
            $updatedContent = str_replace($existingFillable, $newFillable, $existingContent);

            // Write the updated content back to the model file
            $result = file_put_contents($modelFilePath, $updatedContent);

            // Check if the operation was successful
            if ($result !== false) {
                dd("Columns added to existing fillable in model file successfully.");
            } else {
                dd("Failed to update model file.");
            }
        } else {

            $classEndPosition = strrpos($existingContent, '}');







            // Construct $fillable content
            $fillableContent = "    protected \$fillable = ['" . implode("', '", array_map('trim', $columns)) . "'];\n";

            // Insert $fillable content before the class definition's ending curly brace
            $updatedContent = substr_replace($existingContent, $fillableContent, $classEndPosition, 0);



            // Write the updated content back to the model file
            $result = file_put_contents($modelFilePath, $updatedContent);

            // Check if the operation was successful
            if ($result !== false) {
                dd("New fillable attribute added to model file successfully.");
            } else {
                dd("Failed to update model file.");
            }
        }
    }
}
