<?php
/** @var  TemplateData $table */
use Akceli\TemplateData;
use Illuminate\Support\Str; ?>
<template>
    <div>
<?php foreach ($table->filterDates($table->columns) as $column): ?>
<?php if (($column->getField() === 'id')): ?>
<?php elseif ($column->isString()): ?>
        <app-field-text label="<?=$column->getClientLabel()?>"
                        v-if="true"
                        :validation-errors="validationErrors.<?=$column->getField()?>"
                        v-model="form.<?=$column->getField()?>"/>

<?php elseif ($column->isEnum()): ?>
        <app-field-text label="<?=$column->getClientLabel()?>"
                        v-if="true"
                        :validation-errors="validationErrors.<?=$column->getField()?>"
                        v-model="form.<?=$column->getField()?>"/>

<?php elseif ($column->isInteger()): ?>
        <app-field-text label="<?=$column->getClientLabel()?>"
                        v-if="true"
                        :validation-errors="validationErrors.<?=$column->getField()?>"
                        v-model="form.<?=$column->getField()?>"/>

<?php elseif ($column->isBoolean()): ?>
        <app-field-text label="<?=$column->getClientLabel()?>"
                        v-if="true"
                        :validation-errors="validationErrors.<?=$column->getField()?>"
                        v-model="form.<?=$column->getField()?>"/>

<?php elseif ($column->isTimeStamp()): ?>
        <app-field-text label="<?=$column->getClientLabel()?>"
                        v-if="true"
                        :validation-errors="validationErrors.<?=$column->getField()?>"
                        v-model="form.<?=$column->getField()?>"/>

<?php endif ?>
<?php endforeach; ?>
        <div>
            <app-button rounded :async="save">Save</app-button>
        </div>
    </div>
</template>

<script>
  import AppButton from "../components/AppButton";
  import AppField from "../components/AppField";
  export default {
    name: "[[ModelName]]Form",
    components: {AppField, AppButton},
    props: {
      <?=$table->modelName?>: {
        type: Object,
        default: () => {
          return {
<?php foreach ($table->filterDates($table->columns) as $column): ?>
              <?=$column->getField()?>: null,
<?php endforeach; ?>
          };
        }
      }
    },
    data() {
      return {
        validationErrors: {},
        form: {}
      }
    },
    computed: {
      creating() {
        return !this.form.id;
      },
      updating() {
        return !!this.form.id;
      }
    },
    mounted() {
        this.form = JSON.parse(JSON.stringify(this.<?=$table->modelName?>));
    },
    methods: {
      save() {
        return this.$api.<?=$table->modelNames?>.save(this.form, this.validationErrors);
      },
    }
  }
</script>

<style scoped>

</style>
