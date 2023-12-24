<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Plugins\Schema;

use PhpMyAdmin\Config;
use PhpMyAdmin\ConfigStorage\Relation;
use PhpMyAdmin\Current;
use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Identifiers\DatabaseName;
use PhpMyAdmin\Plugins\Schema\Pdf\PdfRelationSchema;
use PhpMyAdmin\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(PdfRelationSchema::class)]
class PdfRelationSchemaTest extends AbstractTestCase
{
    protected PdfRelationSchema $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $dbi = $this->createDatabaseInterface();
        DatabaseInterface::$instance = $dbi;
        $_REQUEST['page_number'] = 33;
        $_REQUEST['pdf_show_grid'] = true;
        $_REQUEST['pdf_show_color'] = true;
        $_REQUEST['pdf_show_keys'] = true;
        $_REQUEST['pdf_orientation'] = 'orientation';
        $_REQUEST['pdf_show_table_dimension'] = true;
        $_REQUEST['pdf_all_tables_same_width'] = true;
        $_REQUEST['pdf_paper'] = 'paper';
        $_REQUEST['pdf_table_order'] = '';
        $_REQUEST['t_v'] = [1 => '1'];
        $_REQUEST['t_h'] = [1 => '1'];
        $_REQUEST['t_x'] = [1 => '10'];
        $_REQUEST['t_y'] = [1 => '10'];
        $_POST['t_db'] = ['test_db'];
        $_POST['t_tbl'] = ['test_table'];

        Current::$database = 'test_db';
        Config::getInstance()->selectedServer['DisableIS'] = true;

        $this->object = new PdfRelationSchema(new Relation($dbi), DatabaseName::from('test_db'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->object);
    }

    /**
     * Test for construct
     */
    #[Group('large')]
    public function testConstructor(): void
    {
        $this->assertEquals(33, $this->object->getPageNumber());
        $this->assertTrue($this->object->isShowGrid());
        $this->assertTrue($this->object->isShowColor());
        $this->assertTrue($this->object->isShowKeys());
        $this->assertTrue($this->object->isTableDimension());
        $this->assertTrue($this->object->isAllTableSameWidth());
        $this->assertEquals('L', $this->object->getOrientation());
        $this->assertEquals('paper', $this->object->getPaper());
    }
}
