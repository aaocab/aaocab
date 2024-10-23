/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var bookingAccounts = function () {
    this.isUpdating = false;
    this.serviceTaxRate = 5;
    this.perKMCharge = 9;
    this.perMinCharge = 0;
    this.extraVendorShare = 0;
    this.initialExtraCharge = 0;
    this.initialExtraMinCharge = 0;
    this.baseAmount = 0;
    this.discount = 0;
    this.addonCharges = 0;
    this.additionalCharges = 0;
    this.COD = 0;
    this.extraKMCharge = 0;
    this.extraKM = 0;
    this.extraMinCharge = 0;
    this.extraMin = 0;
    this.amountBeforeTax = 0;
    this.GST = 0;
    this.driverAllowance = 0;
    this.tollTax = 0;
    this.stateTax = 0;
    this.airportFee = 0;
    this.extraTollTax = 0;
    this.extraStateTax = 0;
    this.parkingCharge = 0;
    this.totalAmount = 0
    this.kmDriven = 0;
    this.advance = 0;
    this.coinsUsed = 0;
    this.refund = 0;
    this.gozoAmount = 0;
    this.vendorAmount = 0;
    this.vendorCollected = 0;
    this.vendorDue = 0;
    this.customerDue = 0;
    this.gozoDue = 0;

    this.init = function (serviceTaxRate, perKMCharge, perMinCharge) {
        this.serviceTaxRate = serviceTaxRate;
        this.perKMCharge = perKMCharge;
        this.perMinCharge = perMinCharge;
        this.initialExtraCharge = this.getExtraKMCharge();
        this.initialExtraMinCharge = this.getExtraMinCharge();
    };

    this.getVal = function (param) {
        var val = parseInt($("#" + param).val());
        if (isNaN(val))
        {
            val = 0;
        }
        return val;
    };

    this.setVal = function (param, val) {
        $("#" + param).val(val.toString());
    };

    this.getBaseAmount = function () {
        this.baseAmount = this.getVal("BookingInvoice_bkg_base_amount");
        return this.baseAmount;
    };
    this.getDiscount = function () {
        this.discount = this.getVal("BookingInvoice_bkg_discount_amount");
        return this.discount;
    };
    this.getAddonCharge = function () {
        this.addonCharges = this.getVal("BookingInvoice_bkg_addon_charges");
        return this.addonCharges;
    };
    
    this.getRefund = function () {
        this.refund = this.getVal("BookingInvoice_bkg_refund_amount");
        return this.refund;
    };
    this.getDriverAllowance = function () {
        this.driverAllowance = this.getVal("BookingInvoice_bkg_driver_allowance_amount");
        return this.driverAllowance;
    };
    this.getAdditionalCharges = function () {
        this.additionalCharges = this.getVal("BookingInvoice_bkg_additional_charge");
        return this.additionalCharges;
    };
    this.getCOD = function () {
        this.COD = this.getVal("BookingInvoice_bkg_convenience_charge");
        return this.COD;
    };
    this.getExtraKMCharge = function () {
        this.extraKMCharge = this.getVal("BookingInvoice_bkg_extra_km_charge");
        return this.extraKMCharge;
    };
    this.getExtraKM = function () {
        this.extraKM = this.getVal("BookingInvoice_bkg_extra_km");
        return this.extraKM;
    };
    this.getExtraMinCharge = function () {
        this.extraMinCharge = this.getVal("BookingInvoice_bkg_extra_total_min_charge");
        return this.extraMinCharge;
    };
    this.getExtraMin = function () {
        this.extraMin = this.getVal("BookingInvoice_bkg_extra_min");
        return this.extraMin;
    };
    this.getGST = function () {
        this.GST = this.getVal("BookingInvoice_bkg_service_tax");
        return this.GST;
    };
    this.getTollTax = function () {
        this.tollTax = this.getVal("BookingInvoice_bkg_toll_tax");
        return this.tollTax;
    };
    this.getStateTax = function () {
        this.stateTax = this.getVal("BookingInvoice_bkg_state_tax");
        return this.stateTax;
    };
    this.getAirportFee = function () {
        this.airportFee = this.getVal("BookingInvoice_bkg_airport_entry_fee");
        return this.airportFee;
    };
    this.getExtraTollTax = function () {
        this.extraTollTax = this.getVal("BookingInvoice_bkg_extra_toll_tax");
        return this.extraTollTax;
    };
    this.getExtraStateTax = function () {
        this.extraStateTax = this.getVal("BookingInvoice_bkg_extra_state_tax");
        return this.extraStateTax;
    };
    this.getParkingCharge = function () {
        this.parkingCharge = this.getVal("BookingInvoice_bkg_parking_charge");
        return this.parkingCharge;
    };
    this.getTotalAmount = function () {
        this.totalAmount = this.getVal("BookingInvoice_bkg_total_amount");
        return this.totalAmount;
    };
    this.getKMDriven = function () {
        this.kmDriven = this.getVal("Booking_bkg_trip_distance");
        return this.kmDriven;
    };
    this.getGozoAmount = function () {
        this.gozoAmount = this.getVal("BookingInvoice_bkg_gozo_amount");
        return this.getVal("BookingInvoice_bkg_gozo_amount");
    };
    this.getVendorAmount = function () {
        this.vendorAmount = this.getVal("BookingInvoice_bkg_vendor_amount");
        return this.vendorAmount;
    };
    this.getAdvance = function () {
        this.advance = this.getVal("BookingInvoice_bkg_advance_amount");
        return this.advance;
    };
    this.getVendorCollected = function () {
        this.vendorCollected = this.getVal("BookingInvoice_bkg_vendor_collected");
        return this.vendorCollected;
    };
    this.getCoinsUsed = function () {
        this.coinsUsed = this.getVal("BookingInvoice_bkg_credits_used");
        return this.coinsUsed;
    };

    this.calculateExtraKMCharge = function () {
        var oldExtraVendorShare = Math.round(this.initialExtraCharge * 0.9);
        this.extraKMCharge = Math.round(this.getExtraKM() * this.perKMCharge);
        this.extraVendorShare = Math.round(this.extraKMCharge * 0.9) - oldExtraVendorShare;
        return this.extraKMCharge;
    };

    this.calculateExtraMinCharge = function () {
        //var oldExtraVendorShare = Math.round(this.initialExtraMinCharge * 0.9);
        this.extraMinCharge = Math.round(this.getExtraMin() * this.perMinCharge);
        //this.extraVendorShare = Math.round(this.extraMinCharge * 0.9) - oldExtraVendorShare;
        return this.extraMinCharge;
    };
    
    this.calculateAmountBeforeTax = function () {
        this.amountBeforeTax = this.getBaseAmount() - this.getDiscount() + this.getAdditionalCharges() + this.getCOD() + this.getAddonCharge() + this.getExtraKMCharge() + this.getExtraMinCharge();
        return this.amountBeforeTax;
    };

    this.calculateGST = function () {
        var GST = Math.round((this.amountBeforeTax + this.tollTax + this.stateTax + this.extraTollTax + this.extraStateTax + this.parkingCharge + this.driverAllowance + this.airportFee) * this.serviceTaxRate * 0.01);    
        return GST;
    };
    
    this.getTotalTollTax = function () {
        var tollTax = this.getTollTax() + this.getExtraTollTax();
        return tollTax;
    };
    
    this.getTotalStateTax = function () {
        var stateTax = this.getStateTax() + this.getExtraStateTax();
        return stateTax;
    };

    this.calculateTotal = function () {
        this.getKMDriven();
        this.getExtraKM();
        this.getExtraMin();
        this.totalAmount = this.calculateAmountBeforeTax() + this.getDriverAllowance() + this.getTotalTollTax() + this.getTotalStateTax() + this.getParkingCharge() + this.getAirportFee() + this.calculateGST();    
        return this.totalAmount;
    };

    this.calculateDue = function () {
        this.calculateTotal();
        this.customerDue = -1 *( this.totalAmount + this.getRefund() - this.getAdvance() - this.getVendorCollected() - this.getCoinsUsed());
        this.vendorDue = this.getVendorAmount() - this.getVendorCollected();
        this.gozoDue = this.getGozoAmount() - this.getAdvance() + this.getRefund() - this.getCoinsUsed();
    };

    this.populateTotalAmount = function () {
        if (this.isUpdating)
        {
            return;
        }
        try {
            this.isUpdating = true;
            this.calculateDue();
            this.setVal("BookingInvoice_bkg_base_amount", this.baseAmount);
            this.setVal("BookingInvoice_bkg_discount_amount", this.discount);
            this.setVal("BookingInvoice_bkg_advance_amount", this.advance);
            this.setVal("BookingInvoice_bkg_refund_amount", this.refund);
            this.setVal("BookingInvoice_bkg_additional_charge", this.additionalCharges);
            this.setVal("BookingInvoice_bkg_convenience_charge", this.COD);
            this.setVal("BookingInvoice_bkg_extra_charge", this.extraKMCharge);
            this.setVal("BookingInvoice_bkg_extra_total_km", this.getExtraKM());
            this.setVal("BookingInvoice_bkg_extra_total_min_charge", this.extraMinCharge);
            this.setVal("BookingInvoice_bkg_extra_min", this.getExtraMin());
            this.setVal("BookingInvoice_bkg_service_tax", this.GST);
            this.setVal("BookingInvoice_bkg_driver_allowance_amount", this.driverAllowance);
            this.setVal("BookingInvoice_bkg_toll_tax", this.getTollTax());
            this.setVal("BookingInvoice_bkg_state_tax", this.getStateTax());
            this.setVal("BookingInvoice_bkg_extra_toll_tax", this.getExtraTollTax());
            this.setVal("BookingInvoice_bkg_extra_state_tax", this.getExtraStateTax());
            this.setVal("BookingInvoice_bkg_parking_charge", this.parkingCharge);
            this.setVal("BookingInvoice_bkg_total_amount", this.totalAmount);
            this.setVal("Booking_bkg_trip_distance", this.getKMDriven());
            this.setVal("BookingInvoice_bkg_gozo_amount", this.gozoAmount);
            this.setVal("BookingInvoice_bkg_vendor_collected", this.vendorCollected);
            this.setVal("BookingInvoice_bkg_credits_used", this.coinsUsed);
            this.setVal("BookingInvoice_bkg_total_amount", this.totalAmount);
            $('#BookingInvoice_bkg_total_amount').change();

        } catch (e) {
            console.log(e);
            alert(e);
        } finally {
            this.isUpdating = false;
        }
    };


};

