import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BookService } from '../../services/book.service';
import  Toastify from 'toastify-js';

@Component({
  selector: 'app-book-form',
  standalone: false,
  templateUrl: './book-form.component.html',
  styleUrl: './book-form.component.scss'
})
export class BookFormComponent implements OnInit {


    tipoSeleccionado: string = 'dni';
  numeroDocumento: string = '';
  maxLength: number = 8;
  bookId?: number;
  listaTipoDocumento = ["DNI","RUC","CARNET DE EXTRANJERIA"];
   hoy = new Date().toISOString().substring(0, 10);

   bookForm: FormGroup = new FormGroup({
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
    private bookService: BookService,
    private router: Router,
    private route: ActivatedRoute
  ) { }

  ngOnInit(): void {
    this.loadDataIntoForm();
  }

    actualizarMaxLength(): void {

       const doc_type = this.bookForm.get('doc_type');
       const doc_number = this.bookForm.get('doc_number');
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

  saveBook(): void {
    if (this.bookId) {
      this.bookService.updateBook(this.bookId, this.bookForm.value).subscribe(book => {
        this.showSuccessToast("Book actualizado con éxito");
        this.router.navigateByUrl('/books');
      });
    } else {
      this.bookService.createBook(this.bookForm.value).subscribe(book => {
        this.showSuccessToast("book agregado con éxito");
        this.router.navigateByUrl('/books');
      });
    }
  }

  hasError(field: string): boolean {
    const errorsObject = this.bookForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);

    if (errors.length && (this.bookForm.get(field)?.touched || this.bookForm.get(field)?.dirty)) {
      return true;
    }

    return false;
  }

  getCurrentError(field: string): string {
    const errorsObject = this.bookForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);

    if (!errors)
      return '';

    return errors[0];
  }

  getFormTitle(): string {
    return this.bookId ? 'Editar book' : 'Nuevo book';
  }

  private loadDataIntoForm(): void {
    this.bookId = Number(this.route.snapshot.paramMap.get('id'));

    if (this.bookId) {
      this.bookService.getBook(this.bookId).subscribe(book => this.bookForm.patchValue(book));
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
