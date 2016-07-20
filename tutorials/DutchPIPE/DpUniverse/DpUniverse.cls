<refentry id="{@id}">
    <refnamediv>
        <refname>DpUniverse</refname>
        <refpurpose>USING THE DUTCHPIPE UNIVERSE OBJECT</refpurpose>
    </refnamediv>
    {@toc}
    <refsect1 id="{@id introduction}">
        <title>Introduction</title>
        <para>
            The universe object, constructed from the {@link DpUniverse} class
            in {@link dpuniverse.php lib/dpuniverse.php}, is the most important
            object in the system. It keeps track of all objects and simulates
            core concepts such as objects being able to contain other objects,
            timeouts, and so on.
        </para>
    </refsect1>
    <refsect1 id="{@id methods}">
        <title>Methods</title>
        <para>
            There are a number of important methods you can call in the universe
            object.
        </para>
        <para>See the {@link DpUniverse} class source reference page.</para>
    </refsect1>
    <refsect1 id="{@id universeref}">
        <title>Reference to the universe object</title>
        <para>You can obtain a reference to the universe object with:</para>
        <para>{@link get_current_dpuniverse()}</para>
        <para>For example:</para>
        <para><filename>dpuniverse/page/index.php</filename></para>
        <programlisting role="php">
<![CDATA[
public function createDpPage()
{
    $this->setTitle('Home');
    <removed>

    /* Creates a note, moves it here: */
    $note = get_current_dpuniverse()->newDpObject(DPUNIVERSE_OBJ_PATH
        . 'note.php');
    $note->moveDpObject($this);

    <removed>
}
]]>
        </programlisting>
    </refsect1>
</refentry>

