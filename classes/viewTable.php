<?php

class viewTable
{
    protected $tableHeads;
    protected $options;
    /*
     *  Initialize with "good" and "bad" data sets.
     * @return void
    */
    public function __construct($config, peopleObjectHandler $peeps)
    {
        $this->tableHeads = $config['tableHeads'];
        $this->options = $config['options'];

        $this->people = $peeps->get();
        $this->badPeople = $peeps->getBad();
        $this->output = '';
    }

    /*
     * Generate an HTML table based on the data set given.
     *
     * @return string
     */
    public function buildTable($col = 'FName', $peeps, $tableSep)
    {
        // Grab keys from first element. If there are no elements this function shouldn't be called.
        $filter = $peeps[0]->filter;
        $table = $this->headers($filter, $col, $peeps, $tableSep);
        $table .= $this->tableBody($filter, $peeps);
        $table .= "</table>\n";

        return $table;
    }

    /*
     * Build table headers
     *
     * @return string
     */
    public function headers($filter, $col, $peeps, $tableSep)
    {
        if ($peeps[0]->isValid()) {
            $errors = '';
        } else {
            $errors = "<th>Problem</th>";
        }
        $table = "<table id='" . $tableSep['name'] . "'>\n<tr>";
        foreach ($filter as $key) {
            $style = $key == $col ? 'selected' : 'notSelected';
            $table .= "<th id='$key" . $tableSep['id'] . "' class='$style' onclick='sort(this.id)'>" . $this->tableHeads[$key] . "</th>";
        }
        return $table . $errors . "</tr>\n";
    }

    /*
     * Build table body
     *
     * @return string
     */
    public function tableBody($filter, $peeps)
    {
        $table = '';
        foreach ($peeps as $aPerson) {
            $table .= "<tr>";
            foreach ($filter as $key) {
                $table .= "<td >" . $aPerson->getField($key) . "</td>";
            }
            if (!$aPerson->isValid()) {
                $table .= "<td>" . $aPerson->getRejection() . "</td>";
            }
            $table .= "</tr>\n";
        }
        return $table;
    }

    /*
     * Generate "good" data set table.
     *
     * @return string
     */
    public function setGoodTable($col = 'FName')
    {
        // If there are no "good" people, there must have been a problem reading the data. Perhaps the json is malformed.
        if ($this->people) {
            $tableTags = ['name' => 'sortable', 'id' => ''];
            $goodTable = $this->buildTable($col, $this->people, $tableTags);
        } else {
            $goodTable = "<p class='error'>There was a problem reading the data source. Please check that it is valid json.</p>";
        }
        return $goodTable;
    }

    /*
     * Generate "bad" data set table.
     *
     * @return string
     */
    public function setBadTable($col = 'FName')
    {

        // If there are no "badPeople" that's ok. The data might be all good!
        if ($this->badPeople) {
            $tableTags = ['name' => 'sortableToo', 'id' => 'Too'];
            $badTable = '<p class="error">Please check the following for missing or invalid data:</p>';
            $badTable .= $this->buildTable($col, $this->badPeople, $tableTags);
        } else {
            $badTable = '';
        }
        return $badTable;
    }


    /*
     * Generate the web page and store in output attribute.
     *
     * @return void
     */
    public function generateHMTL($col = 'FName')
    {
        // Set the good and bad tables, along with the options to choose other data sets.
        if (isset($_SESSION['source'])) {
            $sel = $_SESSION['source'];
        } else $sel = "default";

        $goodTable = $this->setGoodTable($col);
        $badTable = $this->setBadTable($col);
        $options = $this->getOptions($sel);

        $replace = ["{{dataSet}}","{{table}}", '{{options}}', '{{bad}}'];
        $with = [$this->options[$sel] ,$goodTable, $options, $badTable];

        $this->output = str_replace($replace, $with, file_get_contents("views/table.html"));
    }

    /*
     * Generate options dropdown.
     *
     * @return string
     */
    public function getOptions($sel)
    {
        $optionTemplate = '<option {{sel}}value="{{source}}">{{label}}</option>' . "\n";

        $with = '';
        foreach ($this->options as $option => $label) {
            if ($sel == $option) $selected = 'selected="selected" '; else $selected = '';
            $with .= str_replace(['{{sel}}', '{{source}}', '{{label}}'], [$selected, $option, $label], $optionTemplate);
        }

        return $with;
    }

    /*
     * Output the page.
     *
     * @return void
     */
    public function output_page($col = null, $direction = null)
    {
        if ($col && $direction) {
            $this->output = str_replace(['//', '{{col}}', '{{dir}}'], ['', $col, $direction], $this->output);
        }
        echo $this->output;
    }

    /*
     * Output the table rows in json format.
     *
     * @return void
     */
    public function output_json($type)
    {
        $arr = [];
        $peeps = $type == '' ? $this->people : $this->badPeople;
        foreach ($peeps as $aPerson) {
            $arr[] = $aPerson->getFields();
        }

        echo json_encode($arr);
    }

}
