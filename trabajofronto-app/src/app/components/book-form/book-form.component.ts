import { Component, OnInit } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BookService } from '../../services/book.service';
import Toastify from 'toastify-js';

@Component({
  selector: 'app-book-form',
  standalone: false,
  templateUrl: './book-form.component.html',
  styleUrl: './book-form.component.scss'
})
export class BookFormComponent implements OnInit {
  tipoSeleccionado: string = 'dni';
  numeroDocumento: string = '';
  maxLength: number = 13;
  bookId?: number;
  listaTipoDocumento = ["DNI","RUC","CARNET DE EXTRANJERIA"];
  hoy = new Date().toISOString().substring(0, 10);

  selectedFile?: File;
  previewUrl?: string;

  bookForm: FormGroup = new FormGroup({
    isbn: new FormControl('', [Validators.required, Validators.maxLength(13)]),
    name: new FormControl('', [Validators.required, Validators.maxLength(100)]),
    stock: new FormControl('', [Validators.required, Validators.min(0)]),
    price: new FormControl('', [Validators.required, Validators.min(0)]),
    image: new FormControl(''),
    is_actived: new FormControl(1),
    is_deleted: new FormControl(0),
    created_at: new FormControl(this.hoy),
    updated_at: new FormControl(this.hoy),
  });

  constructor(
    private bookService: BookService,
    private router: Router,
    private route: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.loadDataIntoForm();
  }

  onFileChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
      this.selectedFile = input.files[0];
      const reader = new FileReader();
      reader.onload = e => this.previewUrl = reader.result as string;
      reader.readAsDataURL(this.selectedFile);
    }
  }

  saveBook(): void {
  if (this.bookForm.invalid) return;

  // Crear un objeto normal con los valores del formulario
  const bookData = { ...this.bookForm.value };

  // Si tienes la imagen en base64 o URL, asignarla aquí
  if (this.selectedFile) {
    this.convertFileToBase64(this.selectedFile).then(base64 => {
      bookData.image = base64;

      this.sendBookData(bookData);
    }).catch(error => {
      console.error("Error al convertir la imagen:", error);
      // Manejar error de conversión aquí
    });
  } else {
    // Sin imagen, enviar directamente
    this.sendBookData(bookData);
  }
}

private sendBookData(bookData: any): void {
  const request = this.bookId
    ? this.bookService.updateBook(this.bookId, bookData)
    : this.bookService.createBook(bookData);

  request.subscribe({
    next: () => {
      this.showSuccessToast(this.bookId ? "Book actualizado con éxito" : "Book agregado con éxito");
      this.router.navigateByUrl('/books');
    },
    error: (err) => {
      console.error(err);
      // Manejar error aquí
    }
  });
}

// Función para convertir archivo a base64
private convertFileToBase64(file: File): Promise<string> {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
      if (typeof reader.result === 'string') {
        resolve(reader.result);
      } else {
        reject('No se pudo leer el archivo como string');
      }
    };
    reader.onerror = error => reject(error);
  });
}


  actualizarMaxLength(): void {
    const doc_type = this.bookForm.get('doc_type');
    const doc_number = this.bookForm.get('doc_number');
    switch (this.tipoSeleccionado) {
      case '1':
        this.maxLength = 8;
        doc_type?.setValue(1);
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
        break;
      case '2':
        this.maxLength = 11;
        doc_type?.setValue(2);
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
        break;
      case '3':
        this.maxLength = 20;
        doc_type?.setValue(3);
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
        break;
      default:
        this.maxLength = 8;
        doc_number?.setValidators([Validators.required, Validators.minLength(this.maxLength)]);
    }
    doc_number?.updateValueAndValidity();
    this.numeroDocumento = '';
  }

  hasError(field: string): boolean {
    const control = this.bookForm.get(field);
    return !!(control && control.invalid && (control.touched || control.dirty));
  }

  getCurrentError(field: string): string {
    const errorsObject = this.bookForm.get(field)?.errors ?? {};
    const errors = Object.keys(errorsObject);
    return errors.length ? errors[0] : '';
  }

  getFormTitle(): string {
    return this.bookId ? 'Editar book' : 'Nuevo book';
  }

  private loadDataIntoForm(): void {
    this.bookId = Number(this.route.snapshot.paramMap.get('id'));

    if (this.bookId) {
      this.bookService.getBook(this.bookId).subscribe(book => {
        this.bookForm.patchValue(book);
        this.previewUrl = book.image; // Mostrar preview de imagen previa
      });
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
