import state from "../State";

export const WSFE_ENVIRONMENT = (state, environment) => state.environment = environment;

export const WSFE_SET_CUSTOMER = (state, customer) => state.wsFe.customer = customer;

export const WSFE_SET_COMPANY = (state, company) => state.wsFe.company = company;

export const WSFE_SET_TYPE_INVOICE = (state, type) => state.wsFe.type = type;

export const WSFE_SET_ITEMS = (state, items) => state.wsFe.items = items;

export const WSFE_SET_CUSTOMER_DOCTIPO_AFIP_CODE = (state, customer_DocTipo_afip_code) => state.wsFe.customer_DocTipo_afip_code = customer_DocTipo_afip_code;

export const WSFE_SET_INVOICE_DATE = (state, invoice_date) => state.wsFe.invoice_date = invoice_date;