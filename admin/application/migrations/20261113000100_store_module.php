<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * School store: books, uniforms, stationery
 *   store_category, store_item            item master (stock cached in siStock)
 *   store_supplier, store_purchase(+item) stock in
 *   store_kit(+item)                      class-wise sets (book set, uniform set)
 *   store_invoice(+item), store_payment   sales, partial payments
 *   store_stock_move                      every stock change (ledger; siStock = SUM(qty))
 */
class Migration_Store_module extends CI_Migration
{
    public function up()
    {
        $q = function ($sql) { $this->db->query($sql); };

        $q("CREATE TABLE IF NOT EXISTS store_category (
            scId INT NOT NULL AUTO_INCREMENT,
            scName VARCHAR(60) NOT NULL,
            scOrder INT NOT NULL DEFAULT 0,
            scActive TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (scId), UNIQUE KEY uq_sc_name (scName)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_item (
            siId INT NOT NULL AUTO_INCREMENT,
            siScId INT NOT NULL,
            siCode VARCHAR(40) NOT NULL,
            siName VARCHAR(150) NOT NULL,
            siSize VARCHAR(30) NULL,
            siCmId INT NULL,
            siUnit VARCHAR(20) NOT NULL DEFAULT 'Nos',
            siPurchasePrice DECIMAL(10,2) NOT NULL DEFAULT 0,
            siSalePrice DECIMAL(10,2) NOT NULL DEFAULT 0,
            siTaxPercent DECIMAL(5,2) NOT NULL DEFAULT 0,
            siReorderLevel DECIMAL(10,2) NOT NULL DEFAULT 0,
            siStock DECIMAL(10,2) NOT NULL DEFAULT 0,
            siActive TINYINT(1) NOT NULL DEFAULT 1,
            siUser INT NULL,
            siTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (siId), UNIQUE KEY uq_si_code (siCode),
            KEY ix_si_cat (siScId), KEY ix_si_class (siCmId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_supplier (
            ssId INT NOT NULL AUTO_INCREMENT,
            ssName VARCHAR(150) NOT NULL,
            ssPhone VARCHAR(20) NULL,
            ssGstin VARCHAR(20) NULL,
            ssAddress VARCHAR(500) NULL,
            ssActive TINYINT(1) NOT NULL DEFAULT 1,
            ssTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (ssId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_kit (
            skId INT NOT NULL AUTO_INCREMENT,
            skName VARCHAR(100) NOT NULL,
            skCmId INT NULL,
            skActive TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (skId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_kit_item (
            skiId INT NOT NULL AUTO_INCREMENT,
            skiSkId INT NOT NULL,
            skiSiId INT NOT NULL,
            skiQty DECIMAL(10,2) NOT NULL DEFAULT 1,
            PRIMARY KEY (skiId), KEY ix_ski_kit (skiSkId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_purchase (
            spId INT NOT NULL AUTO_INCREMENT,
            spAmId INT NOT NULL,
            spSeq INT NOT NULL,
            spNo VARCHAR(30) NOT NULL,
            spSsId INT NOT NULL,
            spBillNo VARCHAR(50) NULL,
            spDate DATE NOT NULL,
            spSubtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
            spTax DECIMAL(12,2) NOT NULL DEFAULT 0,
            spTotal DECIMAL(12,2) NOT NULL DEFAULT 0,
            spRemarks VARCHAR(500) NULL,
            spCancelled TINYINT(1) NOT NULL DEFAULT 0,
            spCancelReason VARCHAR(255) NULL,
            spUser INT NULL,
            spTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (spId), UNIQUE KEY uq_sp_no (spNo), UNIQUE KEY uq_sp_seq (spAmId, spSeq),
            KEY ix_sp_date (spDate), KEY ix_sp_sup (spSsId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_purchase_item (
            spiId INT NOT NULL AUTO_INCREMENT,
            spiSpId INT NOT NULL,
            spiSiId INT NOT NULL,
            spiQty DECIMAL(10,2) NOT NULL,
            spiRate DECIMAL(10,2) NOT NULL,
            spiTaxPercent DECIMAL(5,2) NOT NULL DEFAULT 0,
            spiTax DECIMAL(12,2) NOT NULL DEFAULT 0,
            spiAmount DECIMAL(12,2) NOT NULL DEFAULT 0,
            PRIMARY KEY (spiId), KEY ix_spi_p (spiSpId), KEY ix_spi_item (spiSiId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_invoice (
            sinId INT NOT NULL AUTO_INCREMENT,
            sinAmId INT NOT NULL,
            sinSeq INT NOT NULL,
            sinNo VARCHAR(30) NOT NULL,
            sinDate DATE NOT NULL,
            sinSmId INT NULL,
            sinCustomer VARCHAR(150) NULL,
            sinPhone VARCHAR(20) NULL,
            sinSubtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
            sinTax DECIMAL(12,2) NOT NULL DEFAULT 0,
            sinDiscount DECIMAL(12,2) NOT NULL DEFAULT 0,
            sinTotal DECIMAL(12,2) NOT NULL DEFAULT 0,
            sinRemarks VARCHAR(500) NULL,
            sinCancelled TINYINT(1) NOT NULL DEFAULT 0,
            sinCancelReason VARCHAR(255) NULL,
            sinCancelledBy INT NULL,
            sinCancelledAt DATETIME NULL,
            sinUser INT NULL,
            sinTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (sinId), UNIQUE KEY uq_sin_no (sinNo), UNIQUE KEY uq_sin_seq (sinAmId, sinSeq),
            KEY ix_sin_date (sinDate), KEY ix_sin_student (sinSmId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_invoice_item (
            siiId INT NOT NULL AUTO_INCREMENT,
            siiSinId INT NOT NULL,
            siiSiId INT NOT NULL,
            siiName VARCHAR(200) NOT NULL,
            siiQty DECIMAL(10,2) NOT NULL,
            siiRate DECIMAL(10,2) NOT NULL,
            siiTaxPercent DECIMAL(5,2) NOT NULL DEFAULT 0,
            siiTax DECIMAL(12,2) NOT NULL DEFAULT 0,
            siiAmount DECIMAL(12,2) NOT NULL DEFAULT 0,
            PRIMARY KEY (siiId), KEY ix_sii_inv (siiSinId), KEY ix_sii_item (siiSiId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_payment (
            spayId INT NOT NULL AUTO_INCREMENT,
            spaySinId INT NOT NULL,
            spayDate DATE NOT NULL,
            spayAmount DECIMAL(12,2) NOT NULL,
            spayMode VARCHAR(20) NOT NULL DEFAULT 'Cash',
            spayReference VARCHAR(100) NULL,
            spayUser INT NULL,
            spayTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (spayId), KEY ix_spay_inv (spaySinId), KEY ix_spay_date (spayDate)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $q("CREATE TABLE IF NOT EXISTS store_stock_move (
            smvId INT NOT NULL AUTO_INCREMENT,
            smvSiId INT NOT NULL,
            smvDate DATE NOT NULL,
            smvType VARCHAR(12) NOT NULL,
            smvQty DECIMAL(10,2) NOT NULL,
            smvRefType VARCHAR(12) NULL,
            smvRefId INT NULL,
            smvRemarks VARCHAR(255) NULL,
            smvUser INT NULL,
            smvTs TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (smvId), KEY ix_smv_item (smvSiId, smvDate), KEY ix_smv_ref (smvRefType, smvRefId)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // starter categories
        foreach (array('Books', 'Notebooks', 'Uniform', 'Stationery', 'Others') as $i => $name) {
            if (!$this->db->where('scName', $name)->count_all_results('store_category')) {
                $this->db->insert('store_category', array('scName' => $name, 'scOrder' => $i + 1));
            }
        }

        // menus: Store (with children)
        $parent = $this->_ensure_menu('STORE', 'Store', null, null, 11);
        $children = array(
            array('STORE_INVOICES',  'Sales / Invoices', '/store_invoices'),
            array('STORE_PURCHASES', 'Purchases',        '/store_purchases'),
            array('STORE_STOCK',     'Stock',            '/store_stock'),
            array('STORE_ITEMS',     'Items',            '/store_items'),
            array('STORE_KITS',      'Class Kits',       '/store_kits'),
            array('STORE_SUPPLIERS', 'Suppliers',        '/store_suppliers'),
            array('STORE_REPORTS',   'Store Reports',    '/reports/store'),
        );
        foreach ($children as $i => $c) $this->_ensure_menu($c[0], $c[1], $parent, $c[2], $i + 1);
    }

    public function down()
    {
        foreach (array('store_stock_move', 'store_payment', 'store_invoice_item', 'store_invoice', 'store_purchase_item',
                       'store_purchase', 'store_kit_item', 'store_kit', 'store_supplier', 'store_item', 'store_category') as $t) {
            $this->db->query("DROP TABLE IF EXISTS $t");
        }
        $this->db->where_in('menu_name', array('STORE_INVOICES', 'STORE_PURCHASES', 'STORE_STOCK', 'STORE_ITEMS',
                                                'STORE_KITS', 'STORE_SUPPLIERS', 'STORE_REPORTS'))->delete('menus');
        $this->db->where('menu_name', 'STORE')->delete('menus');
    }

    private function _ensure_menu($name, $display, $parentId, $link, $order)
    {
        $row = $this->db->where('menu_name', $name)->get('menus')->row();
        if ($row) return (int)$row->menu_id;
        $this->db->insert('menus', array(
            'parent_menu_id' => $parentId, 'menu_name' => $name, 'display_name' => $display,
            'menu_link' => $link, 'display_order' => $order, 'status' => 1, 'group' => 2,
        ));
        return (int)$this->db->insert_id();
    }
}
