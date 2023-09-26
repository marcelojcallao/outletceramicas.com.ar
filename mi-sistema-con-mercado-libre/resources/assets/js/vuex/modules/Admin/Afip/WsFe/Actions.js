export const create_invoice_step1 = async (context, payload) => {
    try {

        const response = await axios.post('/api/sale_invoice/generateInvoiceStep1', {
            environment : payload.environment,
            type : payload.type,
            customer : payload.customer,
            company : payload.company,
            items : payload.items,
            invoice_date : payload.invoice_date,
            customer_DocTipo_afip_code : payload.customer_DocTipo_afip_code,
            pedido_cliente_id : payload.pedido_cliente_id,
            comments : payload.comments
        });

        return response;

    } catch (error) {
        throw error;
    }
}

export const generate_nota_credito = async (_, payload) => {
  try {

      const response = await axios.post('/api/sale_invoice/generate_nota_credito', {
          payload
      });

      return response;

  } catch (error) {
      throw error;
  }
}

/* Swal.fire({
    title: 'Submit your Github username',
    input: 'text',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Look up',
    showLoaderOnConfirm: true,
    preConfirm: (login) => {
      return fetch(`//api.github.com/users/${login}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(response.statusText)
          }
          return response.json()
        })
        .catch(error => {
          Swal.showValidationMessage(
            `Request failed: ${error}`
          )
        })
    },
    allowOutsideClick: () => !Swal.isLoading()
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: `${result.value.login}'s avatar`,
        imageUrl: result.value.avatar_url
      })
    }
  }) */
