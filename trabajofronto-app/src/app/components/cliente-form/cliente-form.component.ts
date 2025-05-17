import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ClienteService } from '../../services/cliente.service';
import  Toastify from 'toastify-js';

@Component({
  selector: 'app-cliente-form',
  standalone: false,
  templateUrl: './cliente-form.component.html',
  styleUrl: './cliente-form.component.scss'
})
export class ClienteFormComponent implements OnInit {


    tipoSeleccionado: string = 'dni';
  numeroDocumento: string = '';
  maxLength: number = 8;
  clienteId?: number;
  listaTipoDocumento = ["DNI","RUC","CARNET DE EXTRANJERIA"];
   hoy = new Date().toISOString().substring(0, 10);

   clienteForm: FormGroup = new FormGroup({
    doc_type        : new FormControl('', Validators.required),
    doc_number      : new FormControl('',  [Validators.required, Validators.minLength(this.maxLength), Validators.maxLength(this.maxLength)]),
    first_name : new FormControl('', [Validators.required,  Validators.maxLength(20)]),
    last_name: new FormControl('', [Validators.required,  Validators.maxLength(20)]),
    phone        : new FormControl('', [Validators.required, Validators.minLength(9), Validators.maxLength(9), Validators.pattern('^[0-9]*$')]),
    email      : new FormControl('', [Validators.required, Validators.email]),
    is_active : new FormControl(1),
    is_deleted : new FormControl(0),
    created_at : new FormControl(this.hoy),
   updated_at : new FormControl(this.hoy),

  });


  constructor(
    private clienteService: ClienteService,
    private router: Router,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
    this.loadDataIntoForm();
  }

    actualizarMaxLength(): void {

       const doc_type = this.clienteForm.get('doc_type');
       const doc_number = this.clienteForm.get('doc_number');
    switch (this.tipoSeleccionado) {
      case '1':
        this.maxLength = 8;
        doc_type?.setValue(1);
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
        doc_number?.updateValueAndValidity();
        break;
      case '2':
        this.maxLength = 11;
         doc_type?.setValue(2);
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
        doc_number?.updateValueAndValidity();
        break;
      case '3':
        this.maxLength = 20;
        doc_type?.setValue(3);
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
        doc_number?.updateValueAndValidity();
        break;
      default:
        this.maxLength = 8;
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
        doc_number?.updateValueAndValidity();
    }
    this.numeroDocumento = ''; // Reinicia el valor al cambiar el tipo
  }

  saveCliente(): void {
    if (this.clienteId) {
      this.clienteService.updateCliente(this.clienteId, this.clienteForm.value).subscribe(cliente => {
        this.showSuccessToast("Cliente actualizado con éxito");
        this.router.navigateByUrl('/clientes');
      });
    } else {
      this.clienteService.createCliente(this.clienteForm.value).subscribe(cliente => {
        this.showSuccessToast("Cliente agregado con éxito");
        this.router.navigateByUrl('/clientes');
      });
    }
  }

  hasError(field: string): boolean {
    const errorsObject = this.clienteForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);

    if (errors.length && (this.clienteForm.get(field)?.touched || this.clienteForm.get(field)?.dirty)) {
      return true;
    }

    return false;
  }

  getCurrentError(field: string): string {
    const errorsObject = this.clienteForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);

    if (!errors)
      return '';

    return errors[0];
  }

  getFormTitle(): string {
    return this.clienteId ? 'Editar cliente' : 'Nuevo cliente';
  }

  private loadDataIntoForm(): void {
    this.clienteId = Number(this.route.snapshot.paramMap.get('id'));

    if (this.clienteId) {
      this.clienteService.getCliente(this.clienteId).subscribe(cliente => this.clienteForm.patchValue(cliente));
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

}
