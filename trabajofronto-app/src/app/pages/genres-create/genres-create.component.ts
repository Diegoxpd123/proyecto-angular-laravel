import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { GenreService } from '../../services/genre.service';

@Component({
  standalone: false,
  selector: 'app-genres-create',
  templateUrl: './genres-create.component.html',
  styleUrls: ['./genres-create.component.scss'],
})
export class GenresCreateComponent implements OnInit {
  form!: FormGroup;
  isEditMode = false;
  genreId: number | null = null;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private genreService: GenreService
  ) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      name: ['', Validators.required],
      descripcion: ['']
    });

    this.route.paramMap.subscribe(params => {
      const id = params.get('id');
      if (id) {
        this.isEditMode = true;
        this.genreId = +id;
        this.loadGenre(this.genreId);
      }
    });
  }

  loadGenre(id: number) {
    this.genreService.getById(id).subscribe(genre => {
      this.form.patchValue(genre);
    });
  }

  onSubmit() {
    if (this.form.invalid) return;

    if (this.isEditMode && this.genreId) {
      this.genreService.update(this.genreId, this.form.value).subscribe(() => {
        this.router.navigate(['/genres']);
      });
    } else {
      this.genreService.create(this.form.value).subscribe(() => {
        this.router.navigate(['/genres']);
      });
    }
  }

  cancel() {
    this.router.navigate(['/genres']);
  }
}
