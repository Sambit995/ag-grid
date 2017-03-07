<?php
$key = "Floating Filter Component";
$pageTitle = "JavaScript Floating Grid Filtering";
$pageDescription = "Describes how to implement customer floating filters for ag-Grid";
$pageKeyboards = "JavaScript Grid Floating Filtering";
$pageGroup = "components";
include '../documentation-main/documentation_header.php';
?>

<h2 id="filter-component">Floating Filter Component</h2>


<p>
    Floating Filter components allow you to add your own floating filter types to ag-Grid. Use this when the provided
    floating filters do not meet your requirements.
</p>

<p>
    This page focuses on writing your own floating filters components. To see general information about floating filters
    in ag-Grid check the <a href="../javascript-grid-filtering/#floatingFilter">docs for floating filters</a>.
</p>

<h3>Interface IFloatingFilter</h3>

<p>
    To provide a custom floating filter, you have to provide it through the column definition property
    <i>floatingFilterComponent</i> if using plain JS or <i>floatingFilterComponentFramework</i> if you
    are using your favourite framework.

    For plain JS (<i>floatingFilterComponent</i>) it needs to be provided in the form of a function.
    ag-Grid will call 'new' on this function and treat the generated class instance as a floating filter
    component. A floating filter component class can be any function /class that implements the following interface:
</p>

<pre>interface IFloatingFilter {
    <span class="codeComment">// mandatory methods</span>

    <span class="codeComment">// The init(params) method is called on the floating filter once. See below for details on the parameters.</span>
    init(params: IFilterFloatingParams): void;

    <span class="codeComment">// This is a method that ag-Grid will call every time the model from the associated rich filter
    // for this floating filter changes. Typically this would be used so that you can refresh your UI and show
    // on it a visual representation of the latest model for the filter as it is being updated somewhere else.</span>
    onParentModelChanged(parentModel:any)

    <span class="codeComment">// Returns the dom html element for this floating filter.</span>
    getGui(): HTMLElement;

    <span class="codeComment">// optional methods</span>

    <span class="codeComment">// Gets called when the floating filter is destroyed.
    // Like column headers, the floating filter life span is only when the column is visible,
    // so gets destroyed if column is made not visible or when user scrolls column out of
    // view with horizontal scrolling.</span>
    destroy?(): void;
}</pre>

<h3 id="ifilter-params">IFloatingFilterParams</h3>

<p>
    The method <i>init(params)</i> takes a params object with the items listed below. If the user provides
    params via the <i>colDef.floatingFilterComponentParams</i> attribute, these will be additionally added to the
    params object, overriding items of the same name if a name clash exists.
</p>

<pre>interface IFloatingFilterParams {

    <span class="codeComment">// The column this filter is for</span>
    column: Column;

    <span class="codeComment">// This is the callback you need to invoke from your component every time that you want to update the model
    // from your parent rich filter. In order to make this call you need to be able to produce a model object
    // like the one this rich filter will produce through getModel() after this call is completed, the parent
    // rich filter will be updated and the data on the grid filtered accordingly if applyButton=false.</span>
    onFloatingFilterChanged(change:any): void;

    <span class="codeComment">// This is the callback you need to invoke from your component every time that you want to simulate the user
    // clicking the apply button on the rich filter. If there is no apply button on the rich filter, this callback
    // behaves exactly the same as onFloatingFilterChanged.
    // As onFloatingFilterChanged you need to be able to produce a model object.</span>
    onApplyFilter(change:any): void;

    <span class="codeComment">// This is a shortcut to invoke getModel on the parent rich filter..</span>
    currentParentModel(): any;
}</pre>

<h3 id="lifecycle">Floating Filter LifeCycle</h3>

<p>
    Floating filters do not contain filter state. They show the state of the actual filter. Floating
    filters are only another GUI for the main filter. For this reason, floating filters lifecycle is
    bound to the visibility of the column. So if you hide a column (either set not visible, or
    horizontally scroll the column out of view) then the floating filter GUI component is destroyed.
    If the column comes back into view, it is created again. This is different to column filters,
    where the column filter will exist as long as the filter column exists, regardless of the columns
    visibility.
</p>

<h3 id="example">Custom Floating Filter Example</h3>

<p>
In the following example you can see how the columns Gold, Silver, Bronze and Total have a custom floating filter
NumberFloatingFilter. This filter substitutes the standard floating filter for a slider that the user can move to
adjust how many medals of each column to filter by.
</p>

<p>
This particular example is using JQuery and limits the filter to only filter by 'greaterThan'. If the user moves the
slider to the left corner the filter its removed.
</p>

<show-example example="exampleCustomFloatingFilter"></show-example>


<h3 id="example">Custom Filter And Custom Floating Filter Example</h3>

<p>
This example extends the previous example by also providing its own Custom filter NumberFilter in the gold, silver, bronze and
total columns which now its accessible though the column menu.

In this example is important to note that:
<ol>
    <li>NumberFilter <i>getModel()</i> returns a Number representing the current greater than filter than.</li>
    <li>NumberFilter <i>setModel(model)</i> takes an object that can be of ay type. If the value passed is numeric then the filter
        gets applied with a condition of greater than</li>
    <li>NumberFloatingFilter <i>onParentModelChanged(parentModel)</i>. Receives the product of <i>NumberFilter.getModel</i>
    every time that the NumberFilter model changes</li>
    <li>NumberFloatingFilter calls on <i>params.onFloatingFilterChanged(modelToAccept)</i> every time the user changes
        the slider value. This will cause an automatic call into <i>NumberFilter.setModel(modelToAccept)</i> </li>
    <li>Since NumberFilter <i>onFloatingFilterChanged(applyNow)</i> IS NOT implemented. Every time the user changes the
        slider value the filter gets updated automatically. If this method was implemented it would get call it every
        time the floating filter would change, and it would delegate to it the responsibility to perform the filtering.</li>
</ol>
</p>

<show-example example="exampleCustomFilterAndFloatingFilter"></show-example>

<h3 id="example">Custom Filter And Read-Only Floating Filter Example</h3>

<p>
If you want to provide only a custom filter but don't want to provide a custom floating filter, you can implement the
method <i>Filter.getModelAsString()</i> and you will get for free a read-only floating filter.
</p>

<p>
This example uses the previous custom filter implementing method <i>NumberFilter.getModelAsString()</i>. Note
how there are no custom floating filters and yet each column using NumberFilter (gold, silver, bronze and total), have
a read-only floating filter that gets updated as you change the values from their rich filter
</p>

<show-example example="exampleCustomFilterDefaultFloatingFilter"></show-example>

<?php include '../documentation-main/documentation_footer.php';?>
