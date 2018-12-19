<template>
	<div class="container-fluid d-flex flex-column h-100">
		<div class="row text-center mt-5">
			<div class="col-12">
				<h1>Pokemon Size Comparison</h1>
				<small>inspired from <a target="_blank" href="https://web.facebook.com/BlackOkumura/videos/1265385833603495/">this Facebook post</a></small>
				<br>
				<small v-if="loadedPokemonCount != 0">
					<button @click="loadMore" v-if="loadedPokemonCount%20 === 0" class="nes-btn is-error">
						Load more 20 pokemon
					</button>
				</small>
			</div>
		</div>

		<div style="position: absolute; bottom: 0; width: 50% ash" class="mb-5 ml-5 text-center">
			<div class="row d-flex align-items-end">
				<div class="col-1">
					<img src="/images/ash.png" style="height: 160px" />
					<h6 class="mt-3">Ash</h6>
					<small>(1.6m)</small>
				</div>
				<div class="col-11 d-flex justify-content-start" style="height: 300px">
					<div class="message -left">
						<div class="nes-balloon from-left">
							<p>{{ loading ? 'Summoning all pokemon...' : loadedPokemonCount + ' Pokemon spawned!'}}</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row flex-nowrap mt-auto pb-5 pokemon-list" style="padding-left: 50%">
			<!-- <img src="/images/ash.png" alt="" /> -->

			<div v-for="dt in data" :key="dt.url" style="width: auto" class="d-flex align-items-end flex-column pokemon">
				<a href="#" @click="renderDetail(dt)" data-toggle="modal" data-target="#pokeDetail" class="mt-auto mr-5 text-center">
					<img v-bind:src="dt.image" v-bind:style="{ height: dt.detail.height * 10 + 'px' }" />
					<h6 class="name mt-3">{{ dt.name }}</h6>
					<small>({{ dt.detail.height/10}}m)</small>
				</a>
			</div>
		</div>

		<div class="modal fade align-self-center" id="pokeDetail" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content nes-container is-rounded">
					<div class="modal-header border-0">
						<h5 class="modal-title">Pokemon Detail</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Psst... we still work on this feature.</p>
						<p>Let {{ selectedPokemon.name }} sleep again ;)</p>
					</div>
					<!-- <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div> -->
				</div>
			</div>
		</div>

	</div>
</template>

<script>

var Jimp = require('jimp');

export default {

	mounted() {
		console.log('Component mounted.')
	},
	created() {
		this.render()
	},
	data() {
		return {
			title: 'Pokepedia',
			description: 'This is Pokemon Encyclopedia',
			data: [],
			loading: false,
			loadedPokemonCount: 0,
			selectedPokemon: {}
		}
	},
	methods: {
		render() {
			this.loading = true
			window.axios.get('/api/pokemon/all').then(response => {
				let pokemons = response.data.results
				let promises = []
				pokemons.map((pokemon, index) => promises.push(new Promise(resolve => {
					this.getDetail(index+1).then(poke => {
						this.loading = false
						pokemon.detail = poke.data
						this.trimImage(poke.data.sprites.front_default).then(img => {
							pokemon.image = img
							this.data.push(pokemon)
						})
						console.log(pokemon)
						console.log(pokemon.name + ' has been spawned!')
						// resolve(pokemon)
					})
				})))
				return Promise.all(promises).then(pokemon => {
				})
      })
		},
		getDetail (id) {
      return new Promise((resolve, reject) => {
        window.axios.get(`/api/pokemon/detail/${id}`).then(pokemon => {
					resolve(pokemon)
					this.loadedPokemonCount++
        })
      })
		},
		loadMore () {
			alert('this function will be coming very soon!')
		},
		renderDetail (pokemon) {
			this.selectedPokemon = pokemon 
		},
		trimImage(imageURL) {
			return Jimp.read(imageURL).then(image => {
				return image.autocrop().getBase64Async(Jimp.MIME_PNG)
			})
		}
	}

}
</script>

<style>

.row {
  overflow-x: auto;
  white-space: nowrap;
}
.row > .col-md-2 {
  display: inline-block;
  float: none;
}
.card {
	background-color: transparent !important;
	border: none;
}
.pokemon a {
	color: black !important;
}
.pokemon a:hover {
	text-decoration: none !important;
}
.pokemon a:hover img {
	margin-bottom: 8px;
	/* border-bottom: 1px solid black; */
}
.pokemon .name {
	text-transform: capitalize;
}
.pokemon-list {
	/* z-index: 0; */
}
.ash img {
	/* z-index: 100; */
}
.nes-container {
	margin: initial !important;
}
</style>
