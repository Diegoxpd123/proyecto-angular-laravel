import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { GenreService } from '../../services/genre.service';

@Component({
  standalone: false,
  selector: 'app-genres',
  templateUrl: './genres.component.html',
  styleUrls: ['./genres.component.scss'],
})
export class GenresComponent implements OnInit {
  genres: any[] = [];
  filteredGenres: any[] = [];
  displayedColumns: string[] = ['name', 'descripcion', 'actions'];
  searchText: string = '';

  constructor(private genreService: GenreService, private router: Router) {}

  ngOnInit(): void {
    this.loadGenres();
  }

  goBack(): void {
    this.router.navigate(['/dashboard']);
  }

  loadGenres() {
    this.genreService.getAll().subscribe(data => {
      this.genres = data;
      this.filteredGenres = [...this.genres];
    });
  }

  applyFilter() {
    const filterValue = this.searchText.trim().toLowerCase();
    this.filteredGenres = this.genres.filter(genre =>
      genre.name.toLowerCase().includes(filterValue)
    );
  }

  createGenre() {
    this.router.navigate(['/genres/create']);
  }

  editGenre(genre: any) {
    this.router.navigate(['/genres/edit', genre.id]);
  }

  deleteGenre(id: number) {
    if (confirm('¿Estás seguro de eliminar este género?')) {
      this.genreService.delete(id).subscribe(() => this.loadGenres());
    }
  }
}
