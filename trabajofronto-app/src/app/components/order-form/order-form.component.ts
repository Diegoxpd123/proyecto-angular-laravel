import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { OrderService } from '../../services/order.service';
import { ClienteService } from '../../services/cliente.service';
import { Cliente } from '../../models/cliente.model';
import { BookService } from '../../services/book.service';
import { Book } from '../../models/book.model';
import { DetailService } from '../../services/detail.service';
import { Detail } from '../../models/detail.model';
import  Toastify from 'toastify-js';
import { ZXingScannerComponent } from '@zxing/ngx-scanner';
import { BarcodeFormat } from '@zxing/library';
import { VoucherService } from '../../services/voucher.service';

@Component({
  selector: 'app-order-form',
  standalone: false,
  templateUrl: './order-form.component.html',
  styleUrl: './order-form.component.scss'
})
export class OrderFormComponent  implements OnInit {

  allowedFormats: BarcodeFormat[] = [
  BarcodeFormat.EAN_13,
  BarcodeFormat.CODE_128,
  BarcodeFormat.UPC_A,
  BarcodeFormat.UPC_E,
  BarcodeFormat.CODE_39,
  BarcodeFormat.QR_CODE,
];

  currentDevice: MediaDeviceInfo | null | undefined;
  torchEnabled = false;
  hasDevices = false;
  availableDevices: MediaDeviceInfo[] =[];

  details: Detail[] = [];
  isLoading: boolean = false;
  clienteSeleccionado: any = null;
  tipoOrden: string = 'boleta';
  clientes: Cliente[] = [];
  libros: Book[] = [];
  librosFiltrados: Book[] = [];
  orderId?: number;
   hoy = new Date().toISOString().substring(0, 10);

   orderForm: FormGroup = new FormGroup({
    cliente_id        : new FormControl('', Validators.required),
    voucher_type : new FormControl('', [Validators.required,  Validators.maxLength(1)]),
    voucher_number: new FormControl('B0001-00000'),
    voucher_pdf: new FormControl('asdasdasdasdasdasadsda'),
    created_at : new FormControl(this.hoy),
    updated_at : new FormControl(this.hoy),
    is_deleted : new FormControl(0),
    is_actived : new FormControl(1),

  });


  constructor(
    private voucherService: VoucherService,
    private detailService: DetailService,
    private clienteService: ClienteService,
    private bookService: BookService,
    private orderService: OrderService,
    private router: Router,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
    this.loadDataIntoForm();
  }




saveOrder(): void {
  if (this.orderForm.invalid) {
    console.warn('Formulario inválido:', this.orderForm.value);
    return;
  }

  this.isLoading = true;

  if (this.orderId) {
    // ACTUALIZAR ORDEN
    this.orderService.updateOrder(this.orderId, this.orderForm.value).subscribe(order => {
      const detallesConOrden = this.details.map(d => ({
        ...d,
        order_id: this.orderId
      }));

      let detallesActualizados = 0;

      detallesConOrden.forEach((detalle, index) => {
        // Si tienes un detail.id, decides si haces update o create
        if (detalle.id) {
          this.detailService.updateDetail(detalle.id, detalle).subscribe({
            next: () => {
              detallesActualizados++;
              if (detallesActualizados === detallesConOrden.length) {
                this.finalizarExito();
              }
            },
            error: (err) => {
              console.error('Error al actualizar detalle', err);
              this.isLoading = false;
            }
          });
        } else {
          // crear nuevo detalle si no tiene ID
          this.detailService.createDetail(detalle).subscribe({
            next: () => {
              detallesActualizados++;
              if (detallesActualizados === detallesConOrden.length) {
                this.finalizarExito();
              }
            },
            error: (err) => {
              console.error('Error al crear nuevo detalle', err);
              this.isLoading = false;
            }
          });
        }
      });

      if (detallesConOrden.length === 0) {
        this.finalizarExito();
      }
    });
  } else {
    // CREAR ORDEN
        const client = this.clientes.find(c => c.id === +this.orderForm.value.cliente_id);
        if (!client) {
          this.showSuccessToast('Cliente no encontrado');
          this.isLoading = false;
          return;
        }

        const payload = {
  type: this.orderForm.value.voucher_type,
  products: this.details.map(item => ({
    name: typeof this.libros.find(l => l.id === item.book_id)?.name || 'Producto sin nombre',
    price: item.price.toFixed(2),
    quantity: item.quantity
  })),
  client: {
    type:  'D',
    number: String(client.doc_number),
    name: `${client.first_name} ${client.last_name || ''}`.trim()
  }
};

       this.voucherService.generarComprobante(payload).subscribe({
  next: (response) => {

    // Guardar número y PDF del comprobante
    this.orderForm.patchValue({
      voucher_number: response.data.number,
      voucher_pdf: response.data.pdf
    });

    // Ahora sí, guardar la orden en tu sistema
    this.orderService.createOrder(this.orderForm.value).subscribe({
      next: (order) => {
        const detallesConOrden = this.details.map(d => ({
          ...d,
          order_id: order.id
        }));

        let detallesGuardados = 0;

        detallesConOrden.forEach((detalle) => {
          this.detailService.createDetail(detalle).subscribe({
            next: () => {
              detallesGuardados++;
              if (detallesGuardados === detallesConOrden.length) {
                this.finalizarExito();
              }
            },
            error: (err) => {
              console.error('Error al guardar detalle', err);
              this.isLoading = false;
            }
          });
        });

        if (detallesConOrden.length === 0) {
          this.finalizarExito();
        }
      },
      error: (err) => {
        console.error('Error al guardar orden:', err);
        this.isLoading = false;
      }
    });
  },
  error: (err) => {
    console.error('Error llamando a API de comprobante:', err);
    this.isLoading = false;
  }
});
  }
}



  finalizarExito(): void {
  this.showSuccessToast('Orden se a finalizado con éxito');
  this.isLoading = false;
  this.router.navigateByUrl('/orders');
}

  hasError(field: string): boolean {
    const errorsObject = this.orderForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);

    if (errors.length && (this.orderForm.get(field)?.touched || this.orderForm.get(field)?.dirty)) {
      return true;
    }

    return false;
  }

  getCurrentError(field: string): string {
    const errorsObject = this.orderForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);

    if (!errors)
      return '';

    return errors[0];
  }

  getFormTitle(): string {
    return this.orderId ? 'Editar order' : 'Nuevo order';
  }

  private loadDataIntoForm(): void {

    this.detailService.getDetails().subscribe(details => {
      this.details = details.filter(d => d.order_id === this.orderId);
    });

    this.clienteService.getClientes().subscribe(clientes => {
      this.clientes = clientes;
    });

    this.bookService.getBooks().subscribe(books => {
      this.libros = books;
      this.librosFiltrados = books;
    });

    this.orderId = Number(this.route.snapshot.paramMap.get('id'));

    if (this.orderId) {
      this.orderService.getOrder(this.orderId).subscribe(order => this.orderForm.patchValue(order));
    }
  }




  private showSuccessToast(message: string): void {
    Toastify({
      text: message,
      close: true,
      gravity: "bottom",
      position: "center",
      stopOnFocus: true,
      style: {
        background: "#189586",
      }
    }).showToast();
  }



agregarAlCarrito(libro: any) {
  const item = this.details.find(i => i.book_id === libro.id);
  if (item) {
    item.quantity += 1;
  } else {
    this.details.push({ ...libro, book_id: libro.id, quantity: 1, price: libro.price });
  }
}

removerDelCarrito(libro: any) {
  this.details = this.details.filter(i => i.book_id !== libro.id);
}

filtrarLibros(valor: string): void {
  const termino = valor.trim().toLowerCase();
  this.librosFiltrados = this.libros.filter(libro =>
    libro.name.toLowerCase().includes(termino)
  );
}

filtrarLibrosISBN(valor: string): void {
  const termino = valor.trim().toLowerCase();
  this.librosFiltrados = this.libros.filter(libro =>
    libro.isbn.toLowerCase().includes(termino)
  );
}

getTotalCarrito(): number {
  return this.details.reduce((total, item) => {
    return total + (item.price * Number(item.quantity));
  }, 0);
}


 onCamerasFound(devices: MediaDeviceInfo[]): void {
    this.availableDevices = devices;
    this.hasDevices = devices && devices.length > 0;

    // Selecciona la cámara trasera si hay, sino la primera disponible
    for (const device of devices) {
      if (/back|rear|environment/gi.test(device.label)) {
        this.currentDevice = device;
        break;
      }
    }
    if (!this.currentDevice && devices.length > 0) {
      this.currentDevice = devices[0];
    }
  }

  onCodeScanned(resultString: string): void {
    console.log('Código escaneado:', resultString);
    this.filtrarLibrosISBN(resultString);
  }

}
