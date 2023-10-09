<?php
/**
 * REDCap External Module: Choice Columns
 * @author Luke Stevens, Murdoch Children's Research Institute
 */
namespace MCRI\ChoiceColumns;

use ExternalModules\AbstractExternalModule;

class ChoiceColumns extends AbstractExternalModule
{
    const ACTION_TAG = '@CHOICE-COLUMNS';
    const CONTAINER_CLASS = 'EM-CHOICE-COLUMNS';
    protected $isSurvey = false;
    protected $record;
    protected $event_id;
    protected $instance;
    protected $instrument;
    protected $enhanced_choices;
    protected $taggedFields = [];

    public function redcap_data_entry_form_top($project_id, $record, $instrument, $event_id, $group_id, $repeat_instance) {
        $this->isSurvey = false;
        $this->record = $record;
        $this->instrument = $instrument;
        $this->event_id = $event_id;
        $this->instance = $repeat_instance;
        $this->enhanced_choices = false;
        $this->pageTop();
    }

    public function redcap_survey_page_top($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id, $repeat_instance) {
        global $enhanced_choices;
        $this->isSurvey = true;
        $this->record = $record;
        $this->instrument = $instrument;
        $this->event_id = $event_id;
        $this->instance = $repeat_instance;
        $this->enhanced_choices = (bool)$enhanced_choices;
        $this->pageTop();
    }

    protected function pageTop() {
        // find any tagged non-matrix radio or checkbox fields on current instrument
        $this->setTaggedFields();
        if (is_array($this->taggedFields) && count($this->taggedFields)===0) return;

        $this->initializeJavascriptModuleObject();
        ?>
        <!-- ChoiceColumns: Begin -->
        <style type="text/css">
            .cc-col { 
                display: inline-grid;
                padding-left: calc(var(--bs-gutter-x) * .1); 
                padding-right: calc(var(--bs-gutter-x) * .1); 
                overflow: hidden;
            }
        </style>
        <script type="text/javascript">
            $(function(){
                var module = <?=$this->getJavascriptModuleObjectName()?>;
                module.renderComplete = false;
                module.enhanced = <?=($this->enhanced_choices)?1:0?>;
                module.taggedFields = JSON.parse('<?=json_encode($this->taggedFields)?>');
                
                module.makeChoiceColumns = function(fieldname, nCols) {
                    let enhancedChoices = null; let choices = null;
                    let defaultChoiceContainer = $('tr[sq_id='+fieldname+']').find('[data-kind=field-value]');
                    let cVert = $(defaultChoiceContainer).find('div.choicevert');
                    let cHoriz = $(defaultChoiceContainer).find('span.choicehoriz');

                    if (module.enhanced) {
                        choices = $('tr[sq_id='+fieldname+']').find('div.enhancedchoice_wrapper').find('div.enhancedchoice');
                    } else {
                        choices = $.merge(cVert,cHoriz);
                    }

                    let horizontal = (cHoriz.length>0);
                    let nChoices = choices.length;
                    let nRows = Math.trunc(nChoices/nCols) + ((nChoices % nCols) ? 1 : 0);
                    console.log('field='+fieldname+', choices='+nChoices+' => columns='+nCols+', rows='+nRows);

                    // make new container
                    let maxWidthPc = Math.trunc(100/nCols);
                    let newContainer = '<div class="container enhancedchoice_wrapper" class="<?=static::CONTAINER_CLASS?>">';
                    for (let row = 0; row < nRows; row++) {
                        newContainer += '<div class="row" style="padding:0;">';
                        for (let col = 0; col < nCols; col++) {
                            newContainer += '<div class="cc-col" style="max-width:'+maxWidthPc+'%;" id="'+fieldname+'-r'+row+'c'+col+'"></div>';
                        }
                        newContainer += '</div>';
                    }
                    newContainer += '</div>';

                    $(defaultChoiceContainer).prepend(newContainer);

                    // move choices to new container
                    $(choices).each(function(i,e) {
                        let r; let c;
                        if (horizontal) {
                            // horizontal alignment -> across then down, r then c
                            r = Math.trunc(i/nCols);
                            c = i % nCols;
                        } else {
                            // vertical alignment -> down then across, c then r
                            c = Math.trunc(i/nRows);
                            r = i % nRows;
                        }
                        $(e).removeClass('col-12');
                        $(e).removeClass('col-md-6');
                        $(e).css('text-indent','0');
                        $('[id="'+fieldname+'-r'+r+'c'+c+'"]').append($(e));
                    });
                    module.renderComplete = true;
                };
                module.afterRender(function() {
                    if (module.renderComplete) return;
                    $('div.<?=static::CONTAINER_CLASS?>').remove();
                    module.taggedFields.forEach(element => {
                        let fc = element.split(':');
                        module.makeChoiceColumns(fc[0],fc[1]);
                    });
                });
            });
        </script>
        <!-- ChoiceColumns: End -->
        <?php
    }

    public function setTaggedFields() {
        $this->taggedFields = array();
                
        $instrumentFields = \REDCap::getDataDictionary('array', false, true, $this->instrument);
        
        if ($this->isSurvey && isset($_GET['__page__'])) {
            global $pageFields;
            $thisPageFields = array();
            foreach ($pageFields[$_GET['__page__']] as $pf) {
                $thisPageFields[$pf] = $instrumentFields[$pf];
            }
        } else {
            $thisPageFields = $instrumentFields;
        }

        foreach ($thisPageFields as $fieldName => $fieldDetails) {
            if (!in_array($fieldDetails['field_type'], ['radio','checkbox'])) continue;
            if (!empty($fieldDetails['matrix_group_name'])) continue;

            $fieldAnnotation = \Piping::replaceVariablesInLabel(
                $fieldDetails['field_annotation'], // $label='', 
                $this->record, // $record=null, 
                $this->event_id, // $event_id=null, 
                $this->instance, // $instance=1, 
                array(), // $record_data=array(),
                true, // $replaceWithUnderlineIfMissing=true, 
                null, // $project_id=null, 
                false // $wrapValueInSpan=true
            );

            // @CHOICE-COLUMNS=?
            $matches = array();
            $pattern = "/".static::ACTION_TAG."='?([1-9][0-9]?)'?/";

            if (preg_match($pattern, $fieldAnnotation, $matches)) {
                $cols = (array_key_exists(1, $matches)) ? intval($matches[1]) : 0;
                if ($cols>0) $this->taggedFields[] = "$fieldName:$cols";
            }
        }
    }
}