<?php

namespace CrudGenerator;

use CrudGenerator\Console\Commands\CrudGeneratorCommand;
use CrudGenerator\Modifiers\Builders\Builder;
use CrudGenerator\Modifiers\ClassModifier;

class GeneratorFlowController
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var CrudGeneratorCommand
     */
    private $output;

    /**
     * @var bool
     */
    private $force;

    /**
     * @var File
     */
    private $file;

    /**
     * GeneratorFlowController constructor
     *
     * @param Parser $parser
     * @param Schema $schema
     * @param CrudGeneratorCommand $output
     * @param bool $force
     */
    function __construct(Parser $parser, Schema $schema, $output, $force = false)
    {
        $this->parser = $parser;
        $this->schema = $schema;
        $this->output = $output;
        $this->force = $force;

        $this->classModifier = new ClassModifier(
            $this->parser,
            $this->schema,
            $this->output,
            $force
        );

        $this->file = new File(app_path());
    }

    public function start()
    {
        foreach ($this->schema->getPolymorphicManyToManyInterfaces() as $interface) {
            $this->setPolymorphicManyToManyRelationships($interface);

            return;
        }

        if (count($this->schema->getBelongsToManyRelationships())) {
            $this->setBelongsToManyRelationship($this->schema->getBelongsToManyRelationships());

            return;
        }

        foreach ($this->schema->getPolymorphicRelationships() as $relationship) {
            $this->setPolymorphicRelationships($relationship);
        }

        foreach ($this->schema->getBelongsToRelationships() as $relationship) {
            $this->setBelongsToRelationship($relationship);
        }

    }

    private function setPolymorphicRelationships($relationship)
    {
        $relationship = studly_case($relationship);
        $otherModel = str_singular(studly_case($this->schema->getTable()));

        $relationshipChoice = $this->output->choice(
            "Dose a {$relationship} have one or many " . str_plural($otherModel) . "?",
            [
                "0: Don't Set Up the other relationship",
                "1: {$relationship} has one {$otherModel}",
                "2: {$relationship} has many " . str_plural($otherModel),
            ]
        );

        if ($relationshipChoice == 1) {
            $method = $relationship;
        } elseif ($relationshipChoice == 2) {
            $method = str_plural($relationship);
        } else {
            return;
        }

        if ($this->classModifier->classHasMethod($this->file->findByTableName($this->schema->getTable()), $method)) {
            return;
        }

        $interface = $this->output->ask(
            "What is the name of the Interface that " . str_plural($relationship) . " will implement"
        );
        $interface = str_replace('Interface', '', $interface);

        $this->getBuilder('Interface')->analise($relationship, $interface);
        $this->getBuilder('Trait')->analise($relationship, $interface);

        if ($relationshipChoice == 1) {
            $this->getBuilder('MorphTo')->analise($relationship, $interface);
            $this->getBuilder('MorphOne')->analise($relationship, $interface);
        } elseif ($relationshipChoice == 2) {
            $this->getBuilder('MorphTo')->analise($relationship, $interface);
            $this->getBuilder('MorphMany')->analise($relationship, $interface);
        }
    }

    private function setBelongsToRelationship($relationship)
    {
        $otherModel = str_singular(studly_case($relationship->REFERENCED_TABLE_NAME));
        $thisModel = str_singular(studly_case($relationship->TABLE_NAME));

        $choice = $this->output->choice(
            "Dose a {$otherModel} have one or many " . str_plural($thisModel) . "?",
            [
                "0: Don't Set Up the other relationship",
                "1: {$otherModel} has one {$thisModel}",
                "2: {$otherModel} has many " . str_plural($thisModel),
            ]
        );

        if ($choice == 1) {
            $this->getBuilder('BelongsTo')->analise($relationship);
            $this->getBuilder('HasOne')->analise($relationship);
        } elseif ($choice == 2) {
            $this->getBuilder('BelongsTo')->analise($relationship);
            $this->getBuilder('HasMany')->analise($relationship);
        }
    }


    private function setBelongsToManyRelationship($relationships)
    {
        $this->getBuilder('BelongsToMany')->analise($relationships);
    }

    private function setPolymorphicManyToManyRelationships($interface)
    {
        $this->output->error("Morphed by many not yet implemented");
        return;
        $this->getBuilder('MorphOne')->analise(null, $interface);
        $interface = $this->output->ask("What is the name of the interface for {$interface}_type");

        $this->getBuilder('Interface')->analise(null, $interface);
        $this->getBuilder('Trait')->analise(null, $interface);

        $this->getBuilder('MorphToMany')->analise(null, $interface);

        // TODO-shawnpivonka Add MorphedByMany template and Builder
//        $this->getBuilder('MorphedByMany')->analise($interface);
    }

    protected function appendToEndOfFile($path, $text, $remove_last_chars = 0, $dont_add_if_exist = false) {
        $content = file_get_contents($path);
        if(!str_contains($content, $text) || !$dont_add_if_exist) {
            $newcontent = substr($content, 0, strlen($content)-$remove_last_chars).$text;
            file_put_contents($path, $newcontent);
        }
    }

    private function getBuilder($relationship_type)
    {
        return Builder::get($relationship_type, $this->classModifier);
    }
}
